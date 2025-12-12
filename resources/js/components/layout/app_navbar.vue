<template>
  <header class="pup-navbar">
    <div class="navbar-container">
      
      <div class="navbar-branding">
        <div class="logo-circle">
          <span class="logo-text">LOGO</span> 
        </div>

        <div class="university-info">
          <h1 class="uni-name">Polytechnic University of the Philippines</h1>
          <span class="system-name">T-SIS</span>
        </div>
      </div>

      <div class="navbar-actions">
        
        <nav class="top-links">
          <a href="#" class="header-link">HOMEPAGE</a>
          <span class="separator">|</span>
          <a href="#" class="header-link">COURSE</a>
        </nav>

        <div class="user-menu" @click.stop="toggleUserMenu">
          <button class="user-button" aria-label="User menu" :aria-expanded="isUserMenuOpen">
            <span class="user-label-text">USER</span>
            
            <div class="user-avatar-container">
              <template v-if="userStore.user?.avatar">
                <img :src="userStore.user.avatar" :alt="userStore.user.name || 'User'" class="user-avatar" />
              </template>
              <div v-else class="user-avatar-initials">
                {{ userStore.userInitials }}
              </div>
            </div>
            <div class="user-info">
              <span class="user-name">{{ userStore.user?.firstName || 'User' }}</span>
              <span class="user-email hidden">{{ userStore.user?.email || '' }}</span>
            </div>
            <IconifyIcon class="icon-chevron" :class="{ 'rotate-180': isUserMenuOpen }" icon="mdi:chevron-down" width="16" height="16" />
          </button>
          
          <!-- Dropdown Menu -->
          <div v-if="isUserMenuOpen" class="user-dropdown active">
            <div class="user-dropdown-header">
              <div class="user-avatar-container">
                <template v-if="userStore.user?.avatar">
                  <img :src="userStore.user.avatar" :alt="userStore.user.firstName || 'User'" class="user-avatar" />
                </template>
                <div v-else class="user-avatar-initials">
                  {{ userStore.userInitials }}
                </div>
              </div>
              <div class="user-info">
                <div class="user-name">{{ userStore.user?.firstName }} {{ userStore.user?.lastName || '' }}</div>
                <div class="user-email">{{ userStore.user?.email || '' }}</div>
                <div class="user-status">
                  <span class="status-badge" :class="userStore.user?.status || 'active'">
                    {{ userStore.statusOptions.find(s => s.value === (userStore.user?.status || 'active'))?.label || 'Active' }}
                  </span>
                </div>
              </div>
            </div>
            <ul class="user-dropdown-menu">
              <li>
                <router-link to="/profile" class="user-dropdown-item" @click="closeUserMenu">
                  <IconifyIcon icon="mdi:account" class="menu-icon" />
                  <span>Your Profile</span>
                </router-link>
              </li>
              <li>
                <router-link to="/settings" class="user-dropdown-item" @click="closeUserMenu">
                  <IconifyIcon icon="mdi:cog" class="menu-icon" />
                  <span>Settings</span>
                </router-link>
              </li>
              <li class="divider"></li>
              <li>
                <button class="user-dropdown-item logout" @click="handleLogout">
                  <IconifyIcon icon="mdi:logout" class="menu-icon" />
                  <span>Sign out</span>
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
// Import required Vue composition API functions and stores
import { computed, onMounted, ref, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useThemeStore } from '@/stores/theme.js';
import { useLayoutStore } from '@/stores/layout.js';
import { useUserStore } from '@/stores/user.js';
import { loadIcons } from '@iconify/vue';
import { useAuth } from '@/composables/useAuth';

// Click outside directive
const vClickOutside = {
  beforeMount(el, binding) {
    el.clickOutsideEvent = function(event) {
      if (!(el === event.target || el.contains(event.target))) {
        binding.value();
      }
    };
    document.addEventListener('click', el.clickOutsideEvent);
  },
  unmounted(el) {
    document.removeEventListener('click', el.clickOutsideEvent);
  },
};

// Define the events this component emits to parent components
defineEmits(['toggle-sidebar']);

const route = useRoute();
const router = useRouter();
const themeStore = useThemeStore();
const layoutStore = useLayoutStore();
const userStore = useUserStore();
const auth = useAuth();

// User menu state
const isUserMenuOpen = ref(false);
const loading = ref(false);

// Toggle user menu
const toggleUserMenu = () => {
  isUserMenuOpen.value = !isUserMenuOpen.value;
};

// Close user menu
const closeUserMenu = () => {
  isUserMenuOpen.value = false;
};

// Close menu when clicking outside
const handleClickOutside = (event) => {
  const userMenu = document.querySelector('.user-menu');
  if (userMenu && !userMenu.contains(event.target)) {
    closeUserMenu();
  }
};

// Handle logout
const handleLogout = async () => {
  try {
    loading.value = true;
    await auth.logout();
    userStore.clearUser();
    closeUserMenu();
    router.push({ name: 'login' });
  } catch (error) {
    console.error('Logout failed:', error);
  } finally {
    loading.value = false;
  }
};

// Theme handling using computed property for two-way binding
const currentTheme = computed({
  get: () => themeStore.currentTheme,
  set: (value) => {
    if (value) {
      themeStore.setTheme(value);
    }
  }
});

// Get available themes from store
const availableThemes = computed(() => themeStore.availableThemes);

// Get current theme icon
const currentThemeIcon = computed(() => {
  const theme = themeStore.availableThemes.find(t => t.id === themeStore.currentTheme);
  if (!theme) return 'mdi:theme-light-dark';
  
  const icons = {
    'light': 'mdi:weather-sunny',
    'dark': 'mdi:weather-night',
    'blue': 'mdi:water',
    'green': 'mdi:leaf',
    'midnight': 'mdi:weather-night',
    'dost': 'mdi:theme-light-dark'
  };
  
  return icons[theme.id] || 'mdi:theme-light-dark';
});

// Get current theme name
const currentThemeName = computed(() => {
  const theme = themeStore.availableThemes.find(t => t.id === themeStore.currentTheme);
  return theme ? theme.name : 'Theme';
});

/**
 * Computed property that generates a display-friendly title based on the current route
 * - Handles the root path ('/') specially by returning 'Home'
 * @returns {string} Formatted page title
 */
const currentPageTitle = computed(() => {
  const path = route.path;
  if (path === '/') return 'Home';
  return path.charAt(1).toUpperCase() + path.slice(2);
});

// Navigation items
const navItems = [
  { path: '/', icon: 'mdi:home' },
  { path: '/dashboard', icon: 'mdi:view-dashboard' },
  { path: '/profile', icon: 'mdi:account'},
  { path: '/settings', icon: 'mdi:cog'},
  { path: '/help', icon: 'mdi:help-circle' },
];

onMounted(() => {
  // Load all icons
  loadIcons([
    'mdi:menu', 
    'mdi:account', 
    'mdi:chevron-down',
    'mdi:theme-light-dark',
    'mdi:weather-sunny',
    'mdi:weather-night',
    'mdi:water',
    'mdi:leaf',
    'mdi:home',
    'mdi:view-dashboard',
    'mdi:account',
    'mdi:cog',
    'mdi:help-circle',
    'mdi:palette',
    'mdi:logout'
  ]);
  
  // Add click outside listener
  document.addEventListener('click', handleClickOutside);
});

// Clean up event listener
onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>
