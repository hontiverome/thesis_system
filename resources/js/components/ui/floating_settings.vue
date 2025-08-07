<!--
 * System Name: Theming and UI Framework
 * Component: FloatingSettings
 * Purpose: Provides quick access to theme and layout settings via a floating button
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
.floating-settings {
  position: fixed;
  top: 80px;
  right: -10px;
  z-index: 1000;
}

.settings-button {
  opacity: 0.7;
  transition: opacity 0.2s ease;
}

.settings-button:hover {
  opacity: 1;
}

.settings-button {
  background: var(--primary-color);
  color: white;
  border: none;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  transition: all 0.2s ease;
}

.settings-button:hover {
  transform: rotate(30deg);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.settings-icon {
  width: 24px;
  height: 24px;
}

.settings-panel {
  position: absolute;
  top: 0;
  right: 100%;
  margin-right: 15px;
  width: 360px;
  background: var(--quick-settings-bg);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  overflow: hidden;
  z-index: 1001;
}

.settings-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 16px;
  border-bottom: 1px solid var(--border-color);
}

.settings-header h3 {
  margin: 0;
  font-size: 1rem;
  font-weight: 600;
  color: var(--text-color);
}

.close-button {
  background: none;
  border: none;
  color: var(--text-muted);
  cursor: pointer;
  padding: 4px;
  border-radius: 4px;
}

.close-button:hover {
  background: var(--hover-bg);
  color: var(--text-color);
}

.settings-group {
  padding: 12px 16px;
  border-bottom: 1px solid var(--border-color);
}

.settings-group:last-child {
  border-bottom: none;
}

.settings-group h4 {
  margin: 0 0 12px 0;
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--text-color);
}

/* Theme options */
.theme-options {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
}

.theme-option {
  display: flex;
  flex-direction: column;
  align-items: center;
  background: none;
  border: 1px solid var(--border-color);
  border-radius: 6px;
  padding: 8px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.theme-option:hover {
  border-color: var(--primary-color);
}

.theme-option.active {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 2px var(--primary-color-20);
}

.theme-preview {
  width: 100%;
  height: 50px;
  border-radius: 4px;
  margin-bottom: 6px;
  display: flex;
  align-items: flex-start;
  justify-content: flex-end;
  padding: 4px;
  position: relative;
  overflow: hidden;
}

.theme-accent {
  width: 20px;
  height: 20px;
  border-radius: 4px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.theme-name {
  font-size: 0.75rem;
  color: var(--text-color);
  text-align: center;
}

/* Layout options */
.layout-options {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
  margin-bottom: 8px;
}

.layout-option {
  display: flex;
  flex-direction: column;
  align-items: center;
  background: none;
  border: 1px solid var(--border-color);
  border-radius: 6px;
  padding: 8px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.layout-option:hover {
  border-color: var(--primary-color);
}

.layout-option.active {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 2px var(--primary-color-20);
}

.layout-preview {
  width: 100%;
  height: 40px;
  border-radius: 4px;
  margin-bottom: 6px;
  background: var(--bg-color);
  position: relative;
  overflow: hidden;
  display: flex;
}

.layout-sidebar {
  width: 30%;
  height: 100%;
  background: var(--sidebar-bg);
  border-right: 1px solid var(--sidebar-bg);
}

.layout-navbar {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 6px;
  background: var(--header-bg);
}

.layout-name {
  font-size: 0.75rem;
  color: var(--text-color);
  text-align: center;
}

/* Transitions */
.settings-panel-enter-active,
.settings-panel-leave-active {
  transition: all 0.2s ease;
  transform-origin: top right;
}

.settings-panel-enter-from,
.settings-panel-leave-to {
  opacity: 0;
  transform: scale(0.95) translateY(-10px);
}
</style>
