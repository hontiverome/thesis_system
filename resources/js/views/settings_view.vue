<template>
  <div class="page">
    <header class="settings-header">
      <div class="header-content">
        <span class="header-title">SETTINGS</span>
        <div v-if="saveStatus.show" :class="['status-badge', saveStatus.type]">
          {{ saveStatus.message }}
        </div>
      </div>
    </header>

    <div class="content">
      <section class="form settings-block">
        
        <div class="field">
          <label>Theme & Appearance</label>
          <div class="box input-box">
            <select v-model="currentTheme" class="styled-select">
              <option 
                v-for="theme in availableThemes" 
                :key="theme.id" 
                :value="theme.id"
              >
                {{ theme.name }} {{ theme.id === currentTheme ? '(Current)' : '' }}
              </option>
            </select>
            <span class="icon-arrow">▼</span>
          </div>
        </div>

        <div class="field">
          <label>Navigation Layout</label>
          <div class="radio-group">
            <label class="radio-item" :class="{ 'active': settings.layout.preference === 'both' }">
              <input type="radio" v-model="settings.layout.preference" value="both" @change="updateLayoutPreference">
              <span>Both Sidebar & Navbar</span>
            </label>
            
            <label class="radio-item" :class="{ 'active': settings.layout.preference === 'sidebar' }">
              <input type="radio" v-model="settings.layout.preference" value="sidebar" @change="updateLayoutPreference">
              <span>Sidebar Only</span>
            </label>

            <label class="radio-item" :class="{ 'active': settings.layout.preference === 'navbar' }">
              <input type="radio" v-model="settings.layout.preference" value="navbar" @change="updateLayoutPreference">
              <span>Navbar Only</span>
            </label>
          </div>
        </div>

        <button class="btn orange" @click="handleManualSave">
          Save Preferences
        </button>

      </section>
    </div>

    <footer class="footer">
       <span>T-SIS SYSTEM</span>
    </footer>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useThemeStore } from '@/stores/theme.js';
import { useLayoutStore } from '@/stores/layout.js';

// Initialize Stores
const themeStore = useThemeStore();
const layoutStore = useLayoutStore();

// Local State
const settings = ref({
  layout: {
    preference: layoutStore.layoutPreference
  }
});

const saveStatus = ref({ show: false, message: '', type: 'success' });

// Watchers & Computed
watch(() => layoutStore.layoutPreference, (newValue) => {
  settings.value.layout.preference = newValue;
}, { immediate: true });

const currentTheme = computed({
  get: () => themeStore.currentTheme,
  set: (value) => {
    if (value) themeStore.setTheme(value);
  }
});

const availableThemes = computed(() => themeStore.availableThemes);

// Actions
const showSaveStatus = (message, type = 'success') => {
  saveStatus.value = { show: true, message, type };
  setTimeout(() => { saveStatus.value.show = false; }, 3000);
};

const updateLayoutPreference = () => {
  try {
    layoutStore.setLayoutPreference(settings.value.layout.preference);
    showSaveStatus('Layout updated!');
  } catch (error) {
    showSaveStatus('Error saving layout', 'error');
  }
};

const handleManualSave = () => {
  // Logic for manual save if needed, otherwise just show visual feedback
  showSaveStatus('All settings saved successfully');
};
</script>

<style scoped>
/* RESET & BASE (From Profile) */
* { box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }

.page {
  background: #f4f4f4;
  min-height: 100vh;
  width: 100%;
  display: flex;
  flex-direction: column;
}

/* TOP HEADER (From Profile) */
.settings-header {
  background: linear-gradient(to right, #e8891c, #f6d2a3);
  border-bottom: 1px solid #e0e0e0;
  padding: 0;
  width: 100%;
  flex-shrink: 0;
}

.header-content {
  max-width: 100%;
  padding: 20px 50px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header-title {
  font-size: 30px;
  letter-spacing: 6px;
  font-weight: 900;
  color: #ffffff;
}

/* Status Badge in Header */
.status-badge {
  padding: 5px 15px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  background: white;
  color: #e8891c;
}
.status-badge.error { color: #cc0000; }

/* CONTENT CONTAINER */
.content {
  display: flex;
  justify-content: center; /* Center the single block */
  padding: 40px;
  background: transparent;
  width: 100%;
  flex-grow: 1;
}

/* SETTINGS BLOCK (Based on .form from Profile) */
.settings-block {
  width: 100%;
  max-width: 800px; /* Limit width for readability */
  background: white;
  padding: 40px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  border: 1px solid #e0e0e0;
  height: fit-content;
}

/* FORM ELEMENTS */
.field { 
  margin-bottom: 30px; 
}

label { 
  font-weight: 800;
  margin-bottom: 12px; 
  display: block; 
  color: #000000; 
  font-size: 13px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Box Styling (Reuse Profile .box) */
.box { 
  position: relative; 
  width: 100%;
  border: 1px solid #e8891c;
  background: #fff;
  min-height: 48px;
  display: flex;
  align-items: center;
}

/* Styled Select Dropdown */
.styled-select {
  width: 100%;
  height: 100%;
  border: none;
  background: transparent;
  padding: 0 15px;
  font-size: 16px;
  font-weight: 600;
  color: #000;
  appearance: none; /* Hide default arrow */
  cursor: pointer;
  outline: none;
  z-index: 2;
}

.icon-arrow {
  position: absolute;
  right: 15px;
  font-size: 12px;
  color: #e8891c;
  pointer-events: none;
}

/* Radio Group Styling */
.radio-group {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.radio-item {
  display: flex;
  align-items: center;
  padding: 15px;
  border: 1px solid #e0e0e0;
  cursor: pointer;
  transition: all 0.2s;
  font-weight: 600;
  color: #333;
}

.radio-item:hover, .radio-item.active {
  border-color: #e8891c;
  background: #fff8f0;
}

.radio-item input {
  margin-right: 15px;
  accent-color: #e8891c;
  transform: scale(1.2);
}

/* BUTTONS (From Profile) */
.btn {
  width: 100%;
  padding: 15px;
  margin-top: 10px;
  border: none;
  cursor: pointer;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-size: 14px;
}
.orange { background: #e8891c; color: white; transition: background 0.2s; }
.orange:hover { background: #d67a10; }

/* FOOTER (From Profile) */
.footer {
  background: linear-gradient(to right, #e8891c, #f6d2a3);
  padding: 15px 40px;
  text-align: right;
  color: white;
  font-weight: 700;
  letter-spacing: 1px;
  margin-top: auto;
  flex-shrink: 0;
}

/* Responsive */
@media (max-width: 900px) {
  .content { padding: 20px; }
  .header-content { padding: 20px; }
  .header-title { font-size: 24px; }
}
</style>