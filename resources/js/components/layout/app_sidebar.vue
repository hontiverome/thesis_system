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
    
    <!-- Sidebar header with collapsible title -->
    <div class="sidebar-header">
      <!-- Single element that changes content based on collapsed state -->
      <h2 class="sidebar-title" :class="{ 'collapsed': isCollapsed }" aria-label="Menu">
        {{ isCollapsed ? 'M' : 'Menu' }}
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
    
    <!-- Footer section with theme toggle and user button -->
    <!-- Omit sidebar footer when layout is both -->
    <div class="sidebar-footer" v-if="layoutStore.layoutPreference !== 'both'">
      <button @click="toggleTheme" 
              class="theme-toggle" 
              aria-label="Toggle theme">
        <span class="icon" aria-hidden="true" style="margin-right: 0.15rem">
          <IconifyIcon icon="mdi:palette" width="30" height="30" />
        </span>
        <span class="text" v-if="!isCollapsed" style="margin-right: 1rem">{{ themeButtonText }}</span>
      </button>
      <button class="sidebar-user-button" 
              :class="{ 'collapsed': isCollapsed }"
              aria-label="User account">
        <span class="icon" aria-hidden="true">
          <IconifyIcon icon="mdi:account-circle" width="24" height="24" />
        </span>
        <span class="text" v-if="!isCollapsed">User Account</span>
      </button>
    </div>
  </aside>
</template>

<script setup>
/**
 * Imports Vue's composition API functions and required dependencies
 */
import { computed, onMounted } from 'vue';
import { useThemeStore } from '../../stores/theme';
import { useLayoutStore } from '../../stores/layout';
import { loadIcons } from '@iconify/vue';

/**
 * Component Properties
 * Defines the props that can be passed to this component
 * @property {Boolean} isCollapsed - Controls whether the sidebar is in collapsed state
 */
const props = defineProps({
  isCollapsed: {
    type: Boolean,
    default: false
  }
});

// Initialize the stores
const themeStore = useThemeStore();
const layoutStore = useLayoutStore();

/**
 * Navigation items configuration
 * Defines the main navigation structure with paths, icons, and display text
 * @type {Array<{path: string, icon: string, text: string}>}
 */
const navItems = [
  { path: '/', icon: 'mdi:home', text: 'Home' },
  { path: '/dashboard', icon: 'mdi:view-dashboard', text: 'Dashboard' },
  { path: '/profile', icon: 'mdi:account', text: 'Profile' },
  { path: '/settings', icon: 'mdi:cog', text: 'Settings' },
  { path: '/help', icon: 'mdi:help-circle', text: 'Help' },
];

/**
 * Computed property that determines the text for the theme toggle button
 * Shows the name of the next theme that will be applied when toggling
 * @returns {string} The display name of the next theme
 */
const themeButtonText = computed(() => {
  // Find the index of the current theme
  const currentIndex = themeStore.availableThemes.findIndex(t => t.id === themeStore.currentTheme);
  // Calculate the index of the next theme (wraps around to 0 at the end)
  const nextIndex = (currentIndex + 1) % themeStore.availableThemes.length;
  // Return the name of the next theme
  return themeStore.availableThemes[nextIndex].name;
});

/**
 * Toggles between available themes in the theme store
 * This function is called when the theme toggle button is clicked
 */
const toggleTheme = () => {
  themeStore.toggleTheme();
};

// Load required icons
onMounted(() => {
  loadIcons([
    'mdi:home',
    'mdi:view-dashboard',
    'mdi:account',
    'mdi:cog',
    'mdi:help-circle',
    'mdi:palette',
    'mdi:account-circle'
  ]);
});
</script>
