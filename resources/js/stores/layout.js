/*
 * System Name: Theming and UI Framework
 * Module Name: Layout Settings
 * Purpose Of this file: 
 * Javascript file that handles the logic of layout
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
*/
// Pinia to remember layout and vue framework
import { ref, computed } from 'vue';
import { defineStore } from 'pinia';

export const useLayoutStore = defineStore('layout', () => {
  // Default layout preference
  const layoutPreference = ref('both'); // 'both', 'sidebar', or 'navbar'
  const isSidebarCollapsed = ref(false);
  // Mobile off-canvas sidebar state
  const isMobileSidebarOpen = ref(false);

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

  // Mobile sidebar controls
  const openMobileSidebar = () => { isMobileSidebarOpen.value = true; };
  const closeMobileSidebar = () => { isMobileSidebarOpen.value = false; };
  const toggleMobileSidebar = () => { isMobileSidebarOpen.value = !isMobileSidebarOpen.value; };

  // Computed properties for layout classes
  const layoutClasses = computed(() => ({
    'has-sidebar': layoutPreference.value !== 'navbar',
    'has-navbar': layoutPreference.value !== 'sidebar',
    'sidebar-collapsed': isSidebarCollapsed.value && layoutPreference.value !== 'navbar',
    'mobile-sidebar-open': isMobileSidebarOpen.value,
  }));

  // Initialize the store
  loadPreferences();

  return {
    layoutPreference,
    isSidebarCollapsed,
    isMobileSidebarOpen,
    layoutClasses,
    setLayoutPreference,
    toggleSidebar,
    openMobileSidebar,
    closeMobileSidebar,
    toggleMobileSidebar,
  };
});
