<!--
 * System Name: Theming and UI Framework
 * Module Name: Sidebar
 * Purpose Of this file: 
 * To provide a responsive sidebar navigation with collapsible menu and theme toggle.
 * 
 * Author: Jerome Andrei O. Hontiveros
 * Copyright (C) 2025
 * by the Department of Science and Technology — Project LODI
 * All rights reserved.
 * 
 * Permission is hereby granted, free of charge, to any persons obtaining a copy
 * of this software and associated documentation files, to deal in the Software
 * without restriction, including the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, provided that the
 * above copyright notice(s) and this permission notice appears in all copies of
 * the Software and that both the above copyright notice(s) and this permission
 * notice appear in supporting documentation.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT OF THIRD PARTY RIGHTS.
 * IN NO EVENT SHALL THE COPYRIGHT HOLDER OR HOLDERS INCLUDED IN THIS NOTICE BE
 * LIABLE FOR ANY CLAIM, OR ANY SPECIAL INDIRECT OR CONSEQUENTIAL DAMAGES, OR ANY
 * DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
 * ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF OR IN
 * CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 * 
 * Except as contained in this notice, the name of a copyright holder shall not
 * be used in advertising or otherwise to promote the sale, use or other dealings
 * in this Software without prior written authorization of the copyright holder.
-->
<template>
  <aside class="sidebar" 
         :data-layout-mode="layoutMode"
         :class="{ 'collapsed': isCollapsed }" 
         role="navigation"
         aria-label="Main navigation">
    <!-- Sidebar header with collapsible title and hamburger button -->
    <div class="sidebar-header">
      <!-- Hamburger button - only show in sidebar-only layout -->
      <button v-if="layoutStore.layoutPreference === 'sidebar'" 
              @click="toggleSidebar" 
              class="hamburger-button"
              :class="{ 'collapsed': isCollapsed }"
              aria-label="Toggle sidebar">
        <IconifyIcon icon="mdi:menu" class="hamburger-icon" />
      </button>
      <!-- Title - only show when not in sidebar-only layout or when expanded -->
      <h2 v-if="layoutStore.layoutPreference !== 'sidebar' || !isCollapsed" 
          class="sidebar-title" 
          :class="{ 'collapsed': isCollapsed }" 
          aria-label="Menu">
        {{ isCollapsed && layoutStore.layoutPreference !== 'sidebar' ? 'M' : 'Menu' }}
      </h2>
    </div>
    
    <!-- Main navigation menu -->
    <nav class="sidebar-nav" aria-label="Primary">
      <ul role="menu">
        <!-- Dynamic navigation items -->
        <li v-for="item in navItems" 
            :key="item.path" 
            role="none">
          <router-link :to="item.path" 
                      class="sidebar-nav nav-link" 
                      @click="handleNavClick"
                      :title="item.text"
                      role="menuitem"
                      :aria-current="$route.path === item.path ? 'page' : undefined">
            <span class="icon" aria-hidden="true">
              <IconifyIcon :icon="item.icon" width="20" height="20" />
            </span>
            <span class="text" v-if="!isCollapsed">{{ item.text }}</span>
          </router-link>
        </li>
      </ul>
    </nav>
    
    <!-- Footer section with theme toggle and user menu -->
    <!-- Omit sidebar footer when layout is both -->
    <div class="sidebar-footer" v-if="layoutStore.layoutPreference !== 'both'">
      <!-- Theme Toggle -->
      <button @click="toggleTheme" 
              class="theme-toggle" 
              aria-label="Toggle theme">
        <span class="icon" aria-hidden="true">
          <IconifyIcon icon="mdi:palette" width="20" height="20" />
        </span>
        <span class="theme-toggle-text" v-if="!isCollapsed">{{ themeButtonText }}</span>
      </button>
      
      <!-- User Menu -->
      <button class="sidebar-user-button" 
                ref="userButtonRef"
                @click.stop="toggleUserMenu"
                aria-label="User menu"
                aria-expanded="isUserMenuOpen">
          <div class="sidebar-avatar-container">
            <template v-if="userStore.user?.avatar">
              <img :src="userStore.user.avatar" :alt="userStore.user.name || 'User'" class="sidebar-avatar" />
            </template>
            <div v-else class="sidebar-avatar-initials">
              {{ userStore.userInitials }}
            </div>
          </div>
          <div class="user-info" v-if="!isCollapsed">
            <div class="sidebar-username">{{ userStore.user?.firstName|| 'User' }}</div>
          </div>
          <IconifyIcon v-if="!isCollapsed" icon="mdi:chevron-down" class="sidebar-dropdown-arrow" />
      </button>
        
        <!-- User Dropdown Popover -->
        <Teleport to="body">
          <UserDropdownPopover 
            :is-open="isUserMenuOpen"
            :target-element="userButtonRef"
            :is-sidebar-collapsed="isCollapsed"
            @close="closeUserMenu"
          />
        </Teleport>
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
  { path: '/home', icon: 'mdi:home', text: 'Home' },
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
