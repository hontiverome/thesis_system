<!---
 * System Name: Theming and UI Framework
 * Component: Floating Settings
 * Purpose Of this file: 
 * To display the floating settings component
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
  <div class="floating-settings">
    <button 
      class="settings-button"
      @click="toggleSettings"
      aria-label="Open settings"
      :aria-expanded="isOpen"
    >
      <IconifyIcon icon="mdi:cog" class="settings-icon" />
    </button>
    
    <transition name="settings-panel">
      <div v-if="isOpen" class="settings-panel">
        <div class="settings-header">
          <h3>Quick Settings</h3>
          <button class="close-button" @click="closeSettings" aria-label="Close settings">
            <IconifyIcon icon="mdi:close" width="20" height="20" />
          </button>
        </div>
        
        <!-- Theme Selection -->
        <div class="settings-group">
          <h4>Theme</h4>
          <div class="theme-options">
            <button
              v-for="theme in availableThemes"
              :key="theme.id"
              class="theme-option"
              :class="{ 'active': theme.id === currentTheme }"
              @click="setTheme(theme.id)"
              :aria-label="`Set ${theme.name} theme`"
              :title="theme.name"
            >
              <span class="theme-preview" :style="{ backgroundColor: themeColors[theme.id]?.bg || '#fff' }">
                <span class="theme-accent" :style="{ backgroundColor: themeColors[theme.id]?.accent || '#4f46e5' }"></span>
              </span>
              <span class="theme-name">{{ theme.name }}</span>
            </button>
          </div>
        </div>
        
        <!-- Layout Selection -->
        <div class="settings-group">
          <h4>Layout</h4>
          <div class="layout-options">
            <button
              v-for="option in layoutOptions"
              :key="option.value"
              class="layout-option"
              :class="{ 'active': layoutStore.layoutPreference === option.value }"
              @click="setLayout(option.value)"
              :aria-label="option.label"
              :title="option.label"
            >
              <span class="layout-preview">
                <span class="layout-sidebar" v-if="option.value !== 'navbar'"></span>
                <span class="layout-navbar" v-if="option.value !== 'sidebar'"></span>
              </span>
              <span class="layout-name">{{ option.label }}</span>
            </button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { useThemeStore } from '@/stores/theme';
import { useLayoutStore } from '@/stores/layout';

const themeStore = useThemeStore();
const layoutStore = useLayoutStore();
const isOpen = ref(false);

// Theme colors for preview
const themeColors = {
  light: { bg: '#f9fafb', accent: '#4f46e5' },
  dark: { bg: '#1f2937', accent: '#818cf8' },
  blue: { bg: '#eff6ff', accent: '#3b82f6' },
  green: { bg: '#ecfdf5', accent: '#10b981' },
  midnight: { bg: '#111827', accent: '#8b5cf6' },
  dost: { bg: '#f0f9ff', accent: '#0ea5e9' }
};

// Available themes and layouts
const availableThemes = computed(() => themeStore.availableThemes);
const currentTheme = computed(() => themeStore.currentTheme);

const layoutOptions = [
  { value: 'both', label: 'Both' },
  { value: 'sidebar', label: 'Sidebar Only' },
  { value: 'navbar', label: 'Navbar Only' }
];

// Toggle settings panel
const toggleSettings = () => {
  isOpen.value = !isOpen.value;
};

// Close settings panel
const closeSettings = (e) => {
  if (e) e.stopPropagation();
  isOpen.value = false;
};

// Set theme
const setTheme = (themeId) => {
  themeStore.setTheme(themeId);
  closeSettings();
};

// Set layout
const setLayout = (layout) => {
  layoutStore.setLayoutPreference(layout);
  closeSettings();
};

// Close panel when clicking outside
const handleClickOutside = (event) => {
  const settingsButton = document.querySelector('.settings-button');
  const settingsPanel = document.querySelector('.settings-panel');
  
  if (settingsPanel && settingsButton && 
      !settingsPanel.contains(event.target) && 
      !settingsButton.contains(event.target)) {
    closeSettings();
  }
};

// Add event listeners
onMounted(() => {
  document.addEventListener('click', handleClickOutside);  
});

// Clean up
onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
/* Component-specific styles can be added here if needed */
</style>
