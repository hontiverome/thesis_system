<template>
  <div>
    <DebugAuth /> 
  </div>
  <aside class="sidebar-container">
    
    <div class="sidebar-header">
      <h3>Courses</h3>
    </div>

    <div class="course-card">
      <div class="course-title">MOR</div>
      
      <nav class="course-nav">
        <a href="#" 
           class="nav-item" 
           :class="{ active: activeItem === 'PROPOSAL' }"
           @click.prevent="setActive('PROPOSAL')">
          PROPOSAL
        </a>
        
        <a href="#" 
           class="nav-item" 
           :class="{ active: activeItem === 'CHAPTER 1' }"
           @click.prevent="setActive('CHAPTER 1')">
          CHAPTER 1
        </a>

        <a href="#" 
           class="nav-item" 
           :class="{ active: activeItem === 'CHAPTER 2' }"
           @click.prevent="setActive('CHAPTER 2')">
          CHAPTER 2
        </a>

        <a href="#" 
           class="nav-item" 
           :class="{ active: activeItem === 'CHAPTER 3' }"
           @click.prevent="setActive('CHAPTER 3')">
          CHAPTER 3
        </a>

        <a href="#" 
           class="nav-item" 
           :class="{ active: activeItem === 'OTHERS' }"
           @click.prevent="setActive('OTHERS')">
          OTHERS
        </a>
      </nav>
    </div>

  </aside>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue';
import { useRouter } from 'vue-router';
import { useLayoutStore } from '@/stores/layout';
import { useUserStore } from '@/stores/user';
import { useThemeStore } from '@/stores/theme';
import { Icon as IconifyIcon } from '@iconify/vue';
import UserDropdownPopover from '@/components/ui/user_dropdown_popover.vue';
const router = useRouter();
const layoutStore = useLayoutStore();
const userStore = useUserStore();
const themeStore = useThemeStore();
const userButtonRef = ref(null);
const layoutMode = computed(() => layoutStore.layoutPreference);
const popoverStyle = ref({});


defineOptions({
  components: {
    UserDropdownPopover
  }
});

// On mobile when the off-canvas sidebar is open, force expanded view
const isCollapsed = computed(() => layoutStore.isSidebarCollapsed && !layoutStore.isMobileSidebarOpen);
const isUserMenuOpen = ref(false);

const themeButtonText = computed(() => {
  const currentTheme = themeStore.availableThemes.find(t => t.id === themeStore.currentTheme);
  return currentTheme?.name || 'Theme';
});

const toggleSidebar = () => {
  const isSmall = window.innerWidth <= 1024; // tablet and below
  if (isSmall) {
    // On mobile, toggle the off-canvas sidebar overlay
    layoutStore.toggleMobileSidebar();
  } else {
    // On larger screens, toggle collapsed state
    layoutStore.toggleSidebar();
  }
};

const toggleTheme = () => {
  themeStore.toggleTheme();
};

const toggleUserMenu = (event) => {
  event.stopPropagation();
  isUserMenuOpen.value = !isUserMenuOpen.value;
};

const closeUserMenu = () => {
  isUserMenuOpen.value = false;
};

const handleLogout = async () => {
  try {
    await userStore.logout();
    router.push('/');
  } catch (error) {
    console.error('Logout failed:', error);
  }
};

const handleClickOutside = (event) => {
  const userMenu = event.target.closest('.user-menu-container');
  if (!userMenu && isUserMenuOpen.value) {
    closeUserMenu();
  }
};

// Navigation items
const navItems = [
  { path: '/', icon: 'mdi:home', text: 'Home' },
  { path: '/dashboard', icon: 'mdi:view-dashboard', text: 'Dashboard' },
  { path: '/profile', icon: 'mdi:account', text: 'Profile' },
  { path: '/settings', icon: 'mdi:cog', text: 'Settings' },
  { path: '/help', icon: 'mdi:help-circle', text: 'Help' },
];

// Close mobile sidebar after navigation on small screens
const handleNavClick = () => {
  if (window.innerWidth <= 1024) {
    layoutStore.closeMobileSidebar();
  }
};

// No need to preload icons when using the Icon component directly
// The Icon component will load icons on demand
onMounted(() => {
  // Add click outside listener
  document.addEventListener('click', handleClickOutside);
});

// Clean up event listeners
onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>
