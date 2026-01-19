<template>
  <div class="app" :class="[currentTheme, layoutStore.layoutClasses]" :style="layoutStyles">
    
    <transition name="navbar-slide" mode="out-in">
      <AppNavbar v-if="!isBlankLayout && layoutStore.layoutPreference !== 'sidebar'"
                 @toggle-sidebar="handleToggleSidebar" />
    </transition>
    
    <transition name="sidebar-fade">
      <AppSidebar v-if="!isBlankLayout && layoutStore.layoutPreference !== 'navbar'"
                  :isCollapsed="layoutStore.isSidebarCollapsed" />
    </transition>

    <div v-if="!isBlankLayout && layoutStore.isMobileSidebarOpen" 
         class="backdrop-overlay" 
         @click="layoutStore.closeMobileSidebar()" />
    
    <button
      v-if="!isBlankLayout && layoutStore.layoutPreference === 'sidebar' && !layoutStore.isMobileSidebarOpen"
      class="floating-sidebar-toggle"
      @click="layoutStore.toggleMobileSidebar()"
      aria-label="Toggle sidebar"
    >
      ☰
    </button>

    <main class="main-content" :class="{ 'blank-main' : isBlankLayout}" role="main">
      <div class="content-wrapper" :class="{'full-width': isBlankLayout}">
        <router-view :key="$route.fullPath" />
      </div>
    </main>
    
    <transition name="bottom-nav-slide">
      <MobileBottomNav v-if="!isBlankLayout && layoutStore.layoutPreference === 'navbar'" />
    </transition>
    
    <FloatingSettings />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useThemeStore } from '@/stores/theme.js';
import { useLayoutStore } from '@/stores/layout.js';
import AppNavbar from '@/components/layout/app_navbar.vue';
import AppSidebar from '@/components/layout/app_sidebar.vue';
import MobileBottomNav from '@/components/layout/mobile_bottom_nav.vue';
import FloatingSettings from '@/components/ui/floating_settings.vue';

const themeStore = useThemeStore();
const layoutStore = useLayoutStore();
const route = useRoute();

// Check for Blank Layout Metadata
const isBlankLayout = computed(() => route.meta.layout === 'blank');
const currentTheme = computed(() => themeStore.currentTheme);

const sidebarWidth = 250;
const collapsedSidebarWidth = 60;

const layoutStyles = computed(() => {
  if (isBlankLayout.value) {
    return {
      '--sidebar-width': '0px',
      '--sidebar-collapsed-width': '0px',
      '--mobile-bottom-nav-height': '0px',
      '--header-height': '0px',
      '--sidebar-opacity': '0',
      '--sidebar-visibility': 'hidden',
      '--navbar-display': 'none',
      '--content-padding': '0px'
    };
  }
  const showSidebar = layoutStore.layoutPreference !== 'navbar';
  const collapsedPref = layoutStore.isSidebarCollapsed && showSidebar;
  const mobileOpen = layoutStore.isMobileSidebarOpen;
  const effectiveCollapsed = mobileOpen ? false : collapsedPref;

  return {
    '--sidebar-width': showSidebar
      ? (effectiveCollapsed ? `${collapsedSidebarWidth}px` : `${sidebarWidth}px`)
      : '0px',
    '--sidebar-collapsed-width': `${collapsedSidebarWidth}px`,
    '--mobile-bottom-nav-height': '56px',
    '--header-height': layoutStore.layoutPreference !== 'sidebar' ? '80px' : '0px', // Updated to 80px to match Navbar
    '--sidebar-opacity': showSidebar ? '1' : '0',
    '--sidebar-visibility': showSidebar ? 'visible' : 'hidden',
    '--navbar-display': layoutStore.layoutPreference !== 'sidebar' ? 'flex' : 'none'
  };
});

watch(() => layoutStore.layoutClasses, (newClasses) => {
  const body = document.body;
  ['has-sidebar', 'has-navbar', 'sidebar-collapsed', 'mobile-sidebar-open'].forEach(cls => {
    body.classList.remove(cls);
  });
  Object.entries(newClasses).forEach(([cls, isActive]) => {
    if (isActive) body.classList.add(cls);
  });
}, { immediate: true, deep: true });

const handleToggleSidebar = () => {
  if (layoutStore.layoutPreference === 'navbar') return;
  const isSmall = window.innerWidth <= 1024;
  if (isSmall) {
    layoutStore.toggleMobileSidebar();
  } else {
    layoutStore.toggleSidebar();
  }
};

onMounted(() => {
  document.documentElement.setAttribute('data-theme', currentTheme.value);
});

watch(currentTheme, (newTheme) => {
  document.documentElement.setAttribute('data-theme', newTheme);  
});

watch(() => layoutStore.isMobileSidebarOpen, (open) => {
  document.body.classList.toggle('no-scroll', open);
});
</script>

<style scoped>
.main-content {
  margin-left: var(--sidebar-width);
  margin-top: var(--header-height);
  min-height: calc(100vh - var(--header-height));
  padding: 1.5rem;
  transition: margin-left var(--transition-duration), margin-top var(--transition-duration);
  background-color: var(--bg-color);
}
.blank-main {
  padding: 0 !important;
  background: transparent !important;
  margin-left: 0 !important;
  margin-top: 0 !important;
  min-height: 100vh !important;
}
.content-wrapper {
  max-width: 1600px;
  width: 100%;
  margin: 0 auto;
}
.content-wrapper.full-width {
  max-width: 100% !important;
  padding: 0 !important;
}

@media (max-width: 1024px) {
  .main-content {
    margin-left: 0;
    padding: var(--content-padding, 1rem);
  }
}

.floating-sidebar-toggle {
  position: fixed;
  top: 12px;
  left: 12px;
  z-index: 60;
  width: 40px;
  height: 40px;
  border-radius: 10px;
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  display: none;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

@media (max-width: 1024px) {
  .floating-sidebar-toggle {
    display: inline-flex;
  }
}
</style>