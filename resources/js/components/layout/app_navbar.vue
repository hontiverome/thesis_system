<!--
 * System Name: Human Resource Management Information System
 * Module Name: None
 * Purpose Of this file: 
 * To provide a responsive navigation bar with theme switching and user menu.
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
  <!-- Main navigation bar container -->
  <header class="navbar">
    <div class="navbar-content">
      <!-- Left section -->
      <div class="navbar-left">
        <!-- Button to toggle the sidebar visibility (only show when sidebar is enabled) -->
        <button v-if="layoutStore.layoutPreference !== 'navbar'"
                @click="$emit('toggle-sidebar')" 
                class="menu-button"
                aria-label="Toggle sidebar menu">
          <IconifyIcon icon="mdi:menu" class="menu-icon" aria-hidden="true" />
        </button>
        
        <!-- Dynamic page title that shows current route information -->
        <h1 class="page-title">Theming Test | {{ currentPageTitle }}</h1>
      </div>

      <!-- Center navigation - only show when not in 'both' layout mode -->
      <nav v-if="layoutStore.layoutPreference !== 'both'" class="navbar-nav" aria-label="Primary">
        <ul role="menu">
          <li v-for="item in navItems" 
              :key="item.path" 
              role="none">
            <router-link :to="item.path" 
                        class="nav-link" 
                        :title="item.text"
                        role="menuitem"
                        :aria-current="$route.path === item.path ? 'page' : undefined">
              <span class="icon" aria-hidden="true">
                <IconifyIcon :icon="item.icon" width="20" height="20" />
              </span>
              <span class="text">{{ item.text }}</span>
            </router-link>
          </li>
        </ul>
      </nav>

      <!-- Right section -->
      <div class="navbar-right">
        <!-- Theme selector with icon and dropdown -->
        <div class="theme-selector">
          <IconifyIcon :icon="currentThemeIcon" class="theme-icon" />
          <span class="theme-name">
            {{ currentThemeName }}
          </span>
          <IconifyIcon icon="mdi:chevron-down" class="dropdown-arrow" />
          <select 
            v-model="currentTheme" 
            class="theme-select"
          >
            <option 
              v-for="theme in availableThemes" 
              :key="theme.id" 
              :value="theme.id"
            >
              {{ theme.name }}
            </option>
          </select>
        </div>

        <!-- User menu section -->
        <div class="user-menu" @click.stop="toggleUserMenu">
          <button class="user-button" aria-label="User menu" aria-expanded="isUserMenuOpen">
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

// User menu state
const isUserMenuOpen = ref(false);

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
    await userStore.logout();
    router.push('/');
  } catch (error) {
    console.error('Logout failed:', error);
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
