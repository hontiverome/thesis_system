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
  <div class="settings" role="main" aria-labelledby="settings-title">
    <!-- Page header with title and description -->
    <header class="settings-header">
      <h1 id="settings-title">Settings</h1>
      <p class="subtitle">Customize your application experience</p>
    </header>
    
    <!-- Main settings sections -->
    <div class="settings-sections">
      <!-- Theme Settings Section -->
      <section class="card settings-section" aria-labelledby="theme-settings-heading">
        <div class="section-header">
          <h2 id="theme-settings-heading">
            <span class="icon">🎨</span>
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
              id="theme"
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
      
      <!-- Account Settings Section -->
      <section class="card settings-section" aria-labelledby="account-settings-heading">
        <div class="section-header">
          <h2 id="account-settings-heading">
            <span class="icon">👤</span>
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
import { ref, onMounted, computed } from 'vue';
import { useThemeStore } from '@/stores/theme';

/**
 * Component State and Data
 */

// Store references
const themeStore = useThemeStore();

// Create computed property for two-way binding with theme store
const currentTheme = computed({
  get: () => themeStore.currentTheme,
  set: (value) => {
    if (value) {
      themeStore.setTheme(value);
    }
  }
});

// Form data
const settings = ref({
  account: {
    language: 'en'
  }
});

// Available themes for selection
const availableThemes = computed(() => themeStore.availableThemes);

// Get current theme name for display
const currentThemeName = computed(() => {
  const theme = themeStore.availableThemes.find(t => t.id === themeStore.currentTheme);
  return theme ? theme.name : 'Select a theme';
});

// Get user's timezone
const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

// Available languages for selection
const availableLanguages = [
  { value: 'en', label: 'English' },
  { value: 'es', label: 'Español' },
  { value: 'fr', label: 'Français' },
];
</script>
