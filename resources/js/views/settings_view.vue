<!---
 * System Name: Theming and UI Framework
 * Module Name: Settings
 * Purpose Of this file: 
 * To display the main settings view
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
  <div class="settings page-view" role="main" aria-labelledby="settings-title">
    <!-- Page header with title and description -->
    <header class="settings-header page-header">
      <h1 id="settings-title" class="page-title">Settings</h1>
      <p class="subtitle page-subtitle">Customize your application experience</p>
    </header>
    
    <!-- Save success message -->
    <div v-if="saveStatus.show" :class="['save-status', saveStatus.type]">
      {{ saveStatus.message }}
    </div>
    
    <!-- Main settings sections -->
    <div class="settings-sections">
      <!-- Theme Settings Section -->
      <section class="card settings-section" aria-labelledby="theme-settings-heading">
        <div class="section-header">
          <h2 id="theme-settings-heading">
            <span class="icon">
              <IconifyIcon icon="mdi:palette" width="24" height="24" />
            </span>
            Theme & Appearance
          </h2>
          <p class="section-description">
            Customize the look and feel of the application
          </p>
        </div>
        
        <!--
          Theme selection dropdown with options for each available theme
          The currently selected theme is shown as "(current)" in the dropdown
          and is also highlighted in the list
        -->
        <div class="settings-options">
          <div class="setting-option">
            <label for="theme">Select Theme:</label>
            <select 
              v-model="currentTheme"
            >
              <option 
                v-for="theme in availableThemes" 
                :key="theme.id" 
                :value="theme.id"
              >
                {{ theme.name }}{{ theme.id === currentTheme ? ' (current)' : '' }}
              </option>
            </select>
          </div>
        </div>
      </section>
      
      <!-- Layout Settings Section -->
      <section class="card settings-section" aria-labelledby="layout-settings-heading">
        <div class="section-header">
          <h2 id="layout-settings-heading">
            <span class="icon">
              <IconifyIcon icon="mdi:view-dashboard" width="24" height="24" />
            </span>
            Layout Preferences
          </h2>
          <p class="section-description">
            Choose your preferred navigation layout
          </p>
        </div>
        
        <div class="settings-options">
          <div class="setting-option">
            <div class="radio-group">
              <label>
                <input 
                  type="radio" 
                  v-model="settings.layout.preference" 
                  value="both"
                  @change="updateLayoutPreference"
                >
                <span class="radio-label">
                  <span class="radio-title">Both Sidebar & Navbar</span>
                  <span class="radio-description">Show both navigation elements</span>
                </span>
              </label>
              
              <label>
                <input 
                  type="radio" 
                  v-model="settings.layout.preference" 
                  value="sidebar"
                  @change="updateLayoutPreference"
                >
                <span class="radio-label">
                  <span class="radio-title">Sidebar Only</span>
                  <span class="radio-description">Show only the sidebar navigation</span>
                </span>
              </label>
              <label>
                <input 
                  type="radio" 
                  v-model="settings.layout.preference" 
                  value="navbar"
                  @change="updateLayoutPreference"
                >
                <span class="radio-label">
                  <span class="radio-title">Navbar Only</span>
                  <span class="radio-description">Show only the top navbar</span>
                </span>
              </label>
            </div>
            <div class="save-notice">
              <div class="flex items-start text-sm text-gray-500 mt-2">
                <IconifyIcon icon="mdi:information-outline" class="mt-0.5 mr-4 text-blue-500 text-lg" />
                <span class="leading-tight">Changes to layout preferences are saved automatically.</span>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- Account Settings Section -->
      <section class="card settings-section" aria-labelledby="account-settings-heading">
        <div class="section-header">
          <h2 id="account-settings-heading">
            <span class="icon">
              <IconifyIcon icon="mdi:account-cog" width="24" height="24" />
            </span>
            Account
          </h2>
          <p class="section-description">
            Manage your account settings and preferences
          </p>
        </div>
        
        <div class="settings-options">
          <div class="setting-option">
            <label for="language">Language:</label>
            <select 
              id="language" 
              v-model="settings.account.language"
            >
              <option v-for="lang in availableLanguages" :key="lang.value" :value="lang.value">
                {{ lang.label }}
              </option>
            </select>
          </div>
          
          <div class="setting-option">
            <label>Time Zone:</label>
            <div class="timezone-display">{{ userTimezone }}</div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup>
// Import required Vue composition API functions and stores
import { ref, computed, watch, onMounted } from 'vue';
import { useThemeStore } from '@/stores/theme.js';
import { useLayoutStore } from '@/stores/layout.js';
import { loadIcons } from '@iconify/vue';

/**
 * Component State and Data
 */

// Initialize the stores
const themeStore = useThemeStore();
const layoutStore = useLayoutStore();

// Form data - Initialize first to avoid reference errors
const settings = ref({
  layout: {
    preference: layoutStore.layoutPreference // Initialize with store value
  },
  account: {
    language: 'en'
  }
});

// Watch for layout preference changes to update the UI
watch(() => layoutStore.layoutPreference, (newValue) => {
  // This ensures the radio button stays in sync with the store
  settings.value.layout.preference = newValue;
}, { immediate: true });

// Create computed property for two-way binding with theme store
const currentTheme = computed({
  get: () => themeStore.currentTheme,
  set: (value) => {
    if (value) {
      themeStore.setTheme(value);
    }
  }
});

// Save status state
const saveStatus = ref({
  show: false,
  message: '',
  type: 'success' // 'success' or 'error'
});

// Show save status message
const showSaveStatus = (message, type = 'success') => {
  saveStatus.value = {
    show: true,
    message,
    type
  };
  
  // Hide the message after 3 seconds
  setTimeout(() => {
    saveStatus.value.show = false;
  }, 3000);
};

// Update layout preference in the store
const updateLayoutPreference = () => {
  try {
    layoutStore.setLayoutPreference(settings.value.layout.preference);
    showSaveStatus('Layout preference saved successfully!');
  } catch (error) {
    console.error('Failed to save layout preference:', error);
    showSaveStatus('Failed to save layout preference. Please try again.', 'error');
  }
};

// Available themes for selection
const availableThemes = computed(() => themeStore.availableThemes);

// currentThemeName removed (unused)

// Get user's timezone
const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

// Load required icons
onMounted(() => {
  loadIcons([
    'mdi:palette',
    'mdi:view-dashboard',
    'mdi:account-cog'
  ]);
});

// Available languages for the language selector
const availableLanguages = [
  { value: 'en', label: 'English' },
  { value: 'es', label: 'Español' },
  { value: 'fr', label: 'Français' },
  { value: 'de', label: 'Deutsch' },
  { value: 'ja', label: '日本語' },
  { value: 'zh', label: '中文' },
  { value: 'fil', label: 'Filipino' }
];
</script>
