<script setup>
// ... (imports remain the same)
import { ref, computed, onMounted, onUnmounted } from 'vue';
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
const isUserMenuOpen = ref(false);

const isCollapsed = computed(() => layoutStore.isSidebarCollapsed && !layoutStore.isMobileSidebarOpen);
const themeButtonText = computed(() => {
  const currentTheme = themeStore.availableThemes.find(t => t.id === themeStore.currentTheme);
  return currentTheme?.name || 'Theme';
});

// --- UPDATED NAVIGATION ITEMS ---
const navItems = [
  { path: '/home', icon: 'mdi:home', text: 'Home' }, // Correct path
  { path: '/dashboard', icon: 'mdi:view-dashboard', text: 'Dashboard' },
  { path: '/profile', icon: 'mdi:account', text: 'Profile' },
  { path: '/settings', icon: 'mdi:cog', text: 'Settings' },
  { path: '/help', icon: 'mdi:help-circle', text: 'Help' },
];

// ... (rest of logic: toggleSidebar, toggleTheme, etc. remains the same)

const toggleSidebar = () => {
  const isSmall = window.innerWidth <= 1024;
  if (isSmall) {
    layoutStore.toggleMobileSidebar();
  } else {
    layoutStore.toggleSidebar();
  }
};

const toggleTheme = () => themeStore.toggleTheme();

const toggleUserMenu = (event) => {
  event.stopPropagation();
  isUserMenuOpen.value = !isUserMenuOpen.value;
};

const closeUserMenu = () => {
  isUserMenuOpen.value = false;
};

const handleClickOutside = (event) => {
  if (isUserMenuOpen.value && userButtonRef.value && !userButtonRef.value.contains(event.target)) {
    // Also check if click is inside the popover (if referencing DOM directly)
    // For now, simple check
    closeUserMenu();
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
  <aside class="sidebar" :data-layout-mode="layoutMode" :class="{ 'collapsed': isCollapsed }">
    <div class="sidebar-header">
      <button v-if="layoutStore.layoutPreference === 'sidebar'" @click="toggleSidebar" class="hamburger-button">
        <IconifyIcon icon="mdi:menu" class="hamburger-icon" />
      </button>
      <h2 v-if="layoutStore.layoutPreference !== 'sidebar' || !isCollapsed" class="sidebar-title">
        {{ isCollapsed ? 'M' : 'Menu' }}
      </h2>
    </div>
    
    <nav class="sidebar-nav">
      <ul>
        <li v-for="item in navItems" :key="item.path">
          <router-link :to="item.path" class="sidebar-nav nav-link" @click="handleNavClick">
            <span class="icon"><IconifyIcon :icon="item.icon" width="20" height="20" /></span>
            <span class="text" v-if="!isCollapsed">{{ item.text }}</span>
          </router-link>
        </li>
      </ul>
    </nav>
    
    <div class="sidebar-footer" v-if="layoutStore.layoutPreference !== 'both'">
      <button @click="toggleTheme" class="theme-toggle">
        <span class="icon"><IconifyIcon icon="mdi:palette" width="20" height="20" /></span>
        <span class="theme-toggle-text" v-if="!isCollapsed">{{ themeButtonText }}</span>
      </button>
      
      <button class="sidebar-user-button" ref="userButtonRef" @click.stop="toggleUserMenu">
          <div class="sidebar-avatar-container">
            <div class="sidebar-avatar-initials">{{ userStore.user?.firstName?.charAt(0) || 'U' }}</div>
          </div>
          <div class="user-info" v-if="!isCollapsed">
            <div class="sidebar-username">{{ userStore.user?.firstName || 'User' }}</div>
          </div>
          <IconifyIcon v-if="!isCollapsed" icon="mdi:chevron-down" class="sidebar-dropdown-arrow" />
      </button>
      <Teleport to="body">
          <UserDropdownPopover :is-open="isUserMenuOpen" :target-element="userButtonRef" :is-sidebar-collapsed="isCollapsed" @close="closeUserMenu"/>
      </Teleport>
    </div>
  </aside>
</template>