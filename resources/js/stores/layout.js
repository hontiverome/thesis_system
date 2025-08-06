import { ref, computed } from 'vue';
import { defineStore } from 'pinia';

export const useLayoutStore = defineStore('layout', () => {
  // Default layout preference
  const layoutPreference = ref('both'); // 'both', 'sidebar', or 'navbar'
  const isSidebarCollapsed = ref(false);

  // Load saved preferences from localStorage
  const loadPreferences = () => {
    const savedPreference = localStorage.getItem('layoutPreference');
    if (savedPreference) {
      layoutPreference.value = savedPreference;
    }
    
    const savedSidebarState = localStorage.getItem('sidebarCollapsed');
    if (savedSidebarState !== null) {
      isSidebarCollapsed.value = savedSidebarState === 'true';
    }
  };

  // Save preferences to localStorage
  const savePreference = () => {
    localStorage.setItem('layoutPreference', layoutPreference.value);
  };

  // Set layout preference
  const setLayoutPreference = (preference) => {
    if (['both', 'sidebar', 'navbar'].includes(preference)) {
      layoutPreference.value = preference;
      savePreference();
    }
  };

  // Toggle sidebar collapsed state
  const toggleSidebar = () => {
    isSidebarCollapsed.value = !isSidebarCollapsed.value;
    localStorage.setItem('sidebarCollapsed', isSidebarCollapsed.value);
  };

  // Computed properties for layout classes
  const layoutClasses = computed(() => ({
    'has-sidebar': layoutPreference.value !== 'navbar',
    'has-navbar': layoutPreference.value !== 'sidebar',
    'sidebar-collapsed': isSidebarCollapsed.value && layoutPreference.value !== 'navbar',
  }));

  // Initialize the store
  loadPreferences();

  return {
    layoutPreference,
    isSidebarCollapsed,
    layoutClasses,
    setLayoutPreference,
    toggleSidebar,
  };
});
