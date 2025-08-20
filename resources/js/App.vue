<!--
 * System Name: Theming and UI Framework
 * Module Name: App
 * Purpose Of this file: 
 * Main application component that handles the core layout and theme management.
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
    Main Application Component
    Serves as the root component that structures the entire application layout
    
    Dynamic Classes & Styles:
    - 'currentTheme': Applies the current theme class to the root element
    - 'layoutStyles': Dynamically sets CSS variables for responsive layout
  -->
  <div class="app" :class="[currentTheme, layoutStore.layoutClasses]" :style="layoutStyles">
    <!-- Application Navigation Bar -->
    <AppNavbar v-if="layoutStore.layoutPreference !== 'sidebar'" 
               @toggle-sidebar="handleToggleSidebar" />
    
    <!-- Sidebar Navigation (collapsible / off-canvas on mobile) -->
    <AppSidebar v-if="layoutStore.layoutPreference !== 'navbar'" 
                :isCollapsed="layoutStore.isSidebarCollapsed" />

    <!-- Backdrop overlay for mobile sidebar -->
    <div v-if="layoutStore.isMobileSidebarOpen" class="backdrop-overlay" @click="layoutStore.closeMobileSidebar()" />
    
    <!-- Floating hamburger for sidebar-only layout on mobile -->
    <button
      v-if="layoutStore.layoutPreference === 'sidebar'"
      class="floating-sidebar-toggle"
      @click="layoutStore.toggleMobileSidebar()"
      aria-label="Toggle sidebar"
    >
      ☰
    </button>

    <!-- Main Content Area -->
    <main class="main-content" role="main">
      <div class="content-wrapper">
        <router-view :key="$route.fullPath" />
      </div>
    </main>
    
    <!-- Bottom Mobile Navigation (for navbar-only layout) -->
    <MobileBottomNav v-if="layoutStore.layoutPreference === 'navbar'" />
    
    <!-- Floating Settings Button -->
    <FloatingSettings />
  </div>
</template>

<script setup>
/**
 * Imports Vue's composition API functions and required dependencies
 */
import { ref, computed, onMounted, watch } from 'vue';
import { useThemeStore } from '@/stores/theme.js';
import { useLayoutStore } from '@/stores/layout.js';
import AppNavbar from '@/components/layout/app_navbar.vue';
import AppSidebar from '@/components/layout/app_sidebar.vue';
import MobileBottomNav from '@/components/layout/mobile_bottom_nav.vue';
import FloatingSettings from '@/components/ui/floating_settings.vue';

// Initialize the stores
const themeStore = useThemeStore();
const layoutStore = useLayoutStore();

// Component registration
const components = {
  AppNavbar,
  AppSidebar,
  FloatingSettings,
  MobileBottomNav
};

/**
 * Computed Properties
 */

/**
 * Current theme of the application
 * @type {ComputedRef<string>}
 */
const currentTheme = computed(() => themeStore.currentTheme);

// Layout configuration constants
const sidebarWidth = 250;         // Width of the expanded sidebar in pixels
const collapsedSidebarWidth = 60;  // Width of the collapsed sidebar in pixels

/**
 * Computed styles for dynamic layout adjustments
 * Updates CSS variables based on sidebar state and layout preference
 * @type {ComputedRef<Object>}
 */
const layoutStyles = computed(() => {
  const showSidebar = layoutStore.layoutPreference !== 'navbar';
  const collapsedPref = layoutStore.isSidebarCollapsed && showSidebar;
  const mobileOpen = layoutStore.isMobileSidebarOpen;
  // When mobile overlay is open, force expanded width regardless of collapsedPref
  const effectiveCollapsed = mobileOpen ? false : collapsedPref;

  return {
    '--sidebar-width': showSidebar
      ? (effectiveCollapsed ? `${collapsedSidebarWidth}px` : `${sidebarWidth}px`)
      : '0px',
    '--sidebar-collapsed-width': `${collapsedSidebarWidth}px`,
    '--mobile-bottom-nav-height': '56px',
    '--header-height': layoutStore.layoutPreference !== 'sidebar' ? '60px' : '0px',
    '--sidebar-opacity': showSidebar ? '1' : '0',
    '--sidebar-visibility': showSidebar ? 'visible' : 'hidden',
    '--navbar-display': layoutStore.layoutPreference !== 'sidebar' ? 'flex' : 'none'
  };
});

// Body layout classes are managed by the watcher below (no full overwrite)

// Watch for layout class changes
watch(() => layoutStore.layoutClasses, (newClasses) => {
  const body = document.body;
  // Remove all layout classes
  ['has-sidebar', 'has-navbar', 'sidebar-collapsed', 'mobile-sidebar-open'].forEach(cls => {
    body.classList.remove(cls);
  });
  // Add active layout classes
  Object.entries(newClasses).forEach(([cls, isActive]) => {
    if (isActive) body.classList.add(cls);
  });
}, { immediate: true, deep: true });

// Handle navbar hamburger: on mobile open off-canvas, else toggle collapse
const handleToggleSidebar = () => {
  if (layoutStore.layoutPreference === 'navbar') return;
  const isSmall = window.innerWidth <= 1024; // tablet and below
  if (isSmall) {
    layoutStore.toggleMobileSidebar();
  } else {
    layoutStore.toggleSidebar();
  }
};

/**
 * Lifecycle Hooks
 */

// Component mounted hook
onMounted(() => {
  // Set the initial theme on the HTML element
  document.documentElement.setAttribute('data-theme', currentTheme.value);
});

/**
 * Watchers
 */

// Watch for theme changes and update the HTML attribute
watch(currentTheme, (newTheme) => {
  document.documentElement.setAttribute('data-theme', newTheme);  
  // This allows for theme-specific CSS selectors using [data-theme="theme-name"]
});

// Lock body scroll when mobile sidebar is open
watch(() => layoutStore.isMobileSidebarOpen, (open) => {
  const body = document.body;
  if (open) {
    body.classList.add('no-scroll');
  } else {
    body.classList.remove('no-scroll');
  }
});
</script>

<style scoped>
/**
 * App Component Styles
 * Contains all styles for the main application layout
 */

/* 
 * Main Content Area
 * Handles the main content positioning and responsive behavior
 * Uses CSS variables for dynamic theming and layout adjustments
 */
.main-content {
  /* Position content to the right of the sidebar */
  margin-left: var(--sidebar-width);
  /* Position below the fixed header */
  margin-top: var(--header-height);
  /* Ensure content fills the viewport height minus header */
  min-height: calc(100vh - var(--header-height));
  /* Add padding around content */
  padding: 1.5rem;
  /* Smooth transition for sidebar collapse/expand */
  transition: margin-left var(--transition-duration);
  /* Theme-aware background color */
  background-color: var(--bg-color);
}

/* 
 * Content Wrapper
 * Constrains the content width and centers it on larger screens
 */
.content-wrapper {
  /* Maximum width for content to maintain readability */
  max-width: 1600px;
  width: 100%;
  padding: 0 2rem;
  /* Center the content */
  margin: 0 auto;
  box-sizing: border-box;
}

/* 
 * Responsive Adjustments
 * Handles layout changes for different screen sizes
 */
@media (max-width: 1024px) {
  .main-content {
    /* On mobile, remove sidebar margin for full-width content */
    margin-left: 0;
    /* Adjust padding for smaller screens */
    padding: 1rem;
  }

  /* Floating toggle visible only on mobile */
  .floating-sidebar-toggle {
    display: inline-flex;
  }
}

/* Floating sidebar toggle button */
.floating-sidebar-toggle {
  position: fixed;
  top: 12px;
  left: 12px;
  z-index: 60; /* Above overlay (39) and sidebar (40) */
  width: 40px;
  height: 40px;
  border-radius: 10px;
  border: 1px solid var(--border-color);
  background: var(--card-bg);
  color: var(--text-color);
  box-shadow: 0 2px 6px rgba(0,0,0,0.15);
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  line-height: 1;
  cursor: pointer;
  transition: background var(--transition-duration), box-shadow var(--transition-duration);
  display: none; /* hidden by default; shown in mobile media query */
}

.floating-sidebar-toggle:hover {
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}
</style>
