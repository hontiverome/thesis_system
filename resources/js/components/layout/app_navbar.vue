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
    <!-- Container for navbar content with proper spacing and alignment -->
    <div class="navbar-content">
      <!-- Left section of the navbar containing the menu button and page title -->
      <div class="navbar-left">
        <!-- Button to toggle the sidebar visibility -->
        <button @click="$emit('toggle-sidebar')" 
                class="menu-button"
                aria-label="Toggle sidebar menu">
          <!-- TODO: Add icon for menu toggle button -->
          <IconifyIcon icon="mdi:menu" class="menu-icon" aria-hidden="true" />
        </button>
        
        <!-- Dynamic page title that shows current route information -->
        <h1 class="page-title">Theming Test | {{ currentPageTitle }}</h1>
      </div>
      
      <!-- Right section of the navbar containing theme selector and user menu -->
      <div class="navbar-right">
        <!-- Theme selector -->
        <div class="theme-selector">
          <IconifyIcon :icon="currentThemeIcon" class="theme-icon" />
          <select 
            v-model="currentTheme" 
            @change="setTheme"
            class="theme-select"
            aria-label="Select theme"
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
        <div class="user-menu">
          <button class="user-button" aria-label="User menu">
            <span class="user-avatar" aria-hidden="true">
              <IconifyIcon icon="mdi:account" width="24" height="24" />
            </span>
            <span class="user-name">Jerome</span>
            <IconifyIcon class="icon-chevron" icon="mdi:chevron-down" width="16" height="16" />
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
/**
 * Imports Vue's composition API functions and required dependencies
 */
import { computed, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { loadIcons } from '@iconify/vue';
import { useThemeStore } from '@/stores/theme';

// Define the events this component emits to parent components
defineEmits(['toggle-sidebar']);

const route = useRoute();
const themeStore = useThemeStore();

// Theme handling
const currentTheme = ref(themeStore.currentTheme);
const availableThemes = themeStore.availableThemes;

const setTheme = () => {
  themeStore.setTheme(currentTheme.value);
};

// Get current theme icon
const currentThemeIcon = computed(() => {
  const theme = availableThemes.find(t => t.id === currentTheme.value);
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

onMounted(() => {
  loadIcons([
    'mdi:menu', 
    'mdi:account', 
    'mdi:chevron-down',
    'mdi:theme-light-dark',
    'mdi:weather-sunny',
    'mdi:weather-night',
    'mdi:water',
    'mdi:leaf'
  ]);
});
</script>
