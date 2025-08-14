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
  <!-- 
    Main Sidebar Component
    Provides collapsible navigation with theme toggle functionality
  -->
  <aside class="sidebar" 
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
        <span class="icon" aria-hidden="true" style="margin-right: 0.15rem">
          <IconifyIcon icon="mdi:palette" width="30" height="30" />
        </span>
        <span class="text" v-if="!isCollapsed" style="margin-right: 1rem">{{ themeButtonText }}</span>
      </button>
      
      <!-- User Menu -->
      <div class="user-menu-container" v-click-outside="closeUserMenu">
        <button class="sidebar-user-button" 
                :class="{ 'collapsed': isCollapsed, 'active': isUserMenuOpen }"
                @click.stop="toggleUserMenu"
                aria-label="User menu"
                aria-expanded="isUserMenuOpen">
          <div class="user-avatar-container">
            <template v-if="userStore.user?.avatar">
              <img :src="userStore.user.avatar" :alt="userStore.user.name || 'User'" class="user-avatar" />
            </template>
            <div v-else class="user-avatar-initials">
              {{ userStore.user?.name?.charAt(0) || 'U' }}
            </div>
          </div>
          <div class="user-info" v-if="!isCollapsed">
            <div class="user-name">{{ userStore.user?.name || 'User' }}</div>
            <div class="user-email">{{ userStore.user?.email || 'user@example.com' }}</div>
          </div>
          <IconifyIcon icon="mdi:chevron-down" class="dropdown-arrow" v-if="!isCollapsed" />
        </button>
        
        <!-- User Dropdown Menu -->
        <transition name="fade">
          <div v-if="isUserMenuOpen && !isCollapsed" class="user-dropdown">
            <div class="user-dropdown-header">
              <div class="user-avatar-container">
                <template v-if="userStore.user?.avatar">
                  <img :src="userStore.user.avatar" :alt="userStore.user.name || 'User'" class="user-avatar" />
                </template>
                <div v-else class="user-avatar-initials">
                  {{ userStore.user?.name?.charAt(0) || 'U' }}
                </div>
              </div>
              <div class="user-info">
                <div class="user-name">{{ userStore.user?.name || 'User' }}</div>
                <div class="user-email">{{ userStore.user?.email || 'user@example.com' }}</div>
              </div>
            </div>
            <ul class="user-dropdown-menu">
              <li>
                <router-link to="/profile" class="user-dropdown-item" @click="closeUserMenu">
                  <IconifyIcon icon="mdi:account" class="menu-icon" />
                  <span>Profile</span>
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
                <button class="user-dropdown-item" @click="handleLogout">
                  <IconifyIcon icon="mdi:logout" class="menu-icon" />
                  <span>Logout</span>
                </button>
              </li>
            </ul>
          </div>
        </transition>
      </div>
    </div>
  </aside>
</template>

<script setup>
/**
 * Imports Vue's composition API functions and required dependencies
 */
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '@/stores/user';
import { useThemeStore } from '@/stores/theme';
import { useLayoutStore } from '@/stores/layout';
import { loadIcons } from '@iconify/vue';

// Initialize stores
const userStore = useUserStore();
const themeStore = useThemeStore();
const layoutStore = useLayoutStore();
const router = useRouter();

// State for user menu
const isUserMenuOpen = ref(false);

// Toggle sidebar collapsed state
const toggleSidebar = () => {
  layoutStore.toggleSidebar();
};

// Toggle user menu
const toggleUserMenu = () => {
  isUserMenuOpen.value = !isUserMenuOpen.value;
};

// Close user menu
const closeUserMenu = () => {
  isUserMenuOpen.value = false;
};

// Handle click outside to close menu
const handleClickOutside = (event) => {
  const userMenu = event.target.closest('.user-menu-container');
  if (!userMenu) {
    closeUserMenu();
  }
};

// Handle logout
const handleLogout = async () => {
  try {
    await userStore.logout();
    router.push('/login');
  } catch (error) {
    console.error('Logout failed:', error);
  } finally {
    closeUserMenu();
  }
};

// Theme toggle functionality
const toggleTheme = () => {
  themeStore.toggleTheme();
};

// Computed property for theme button text
const themeButtonText = computed(() => {
  return themeStore.currentTheme.charAt(0).toUpperCase() + themeStore.currentTheme.slice(1);
});

// Computed property for sidebar collapsed state
const isCollapsed = computed(() => layoutStore.isSidebarCollapsed);

// Navigation items
const navItems = [
  { path: '/', icon: 'mdi:home', text: 'Home' },
  { path: '/dashboard', icon: 'mdi:view-dashboard', text: 'Dashboard' },
  { path: '/profile', icon: 'mdi:account', text: 'Profile' },
  { path: '/settings', icon: 'mdi:cog', text: 'Settings' },
  { path: '/help', icon: 'mdi:help-circle', text: 'Help' },
];

// Load required icons
onMounted(() => {
  loadIcons([
    'mdi:home',
    'mdi:view-dashboard',
    'mdi:account',
    'mdi:cog',
    'mdi:help-circle',
    'mdi:palette',
    'mdi:account-circle',
    'mdi:chevron-down',
    'mdi:logout',
  ]);
  
  // Add click outside listener
  document.addEventListener('click', handleClickOutside);
});

// Clean up event listeners
onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>
