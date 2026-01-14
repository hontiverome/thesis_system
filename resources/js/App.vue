<template>
  <div class="app" :style="layoutStyles">
    <AppNavbar 
      v-if="layoutStore.layoutPreference !== 'sidebar'" 
      @toggle-sidebar="layoutStore.toggleSidebar" 
    />
    
    <component 
      :is="currentSidebar" 
      v-if="layoutStore.layoutPreference !== 'navbar'"
      :isCollapsed="layoutStore.isSidebarCollapsed" 
    />

    <main class="main-content">
      <div class="content-wrapper">
        <router-view />
      </div>
    </main>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useLayoutStore } from '@/stores/layout.js';

// Import Sidebars
import StudSidebar from '@/components/layout/stud_sidebar.vue';
import AdviserSidebar from '@/components/layout/adviser_sidebar.vue';
import AppNavbar from '@/components/layout/app_navbar.vue';
import ChairSidebar from '@/components/layout/chair_sidebar.vue';
import FacultySidebar from './components/layout/faculty_sidebar.vue';

/**
 * PROPS: This is the entry point for other developers.
 * They just need to pass the role string here.
 */
const props = defineProps({
  userRole: {
    type: String,
    default: '',
    validator: (value) => ['student', 'adviser', 'faculty', 'chair'].includes(value)
  }
});

const layoutStore = useLayoutStore();

/**
 * Sidebar Registry
 * Maps the userRole prop to the imported component
 */
const currentSidebar = computed(() => {
  const sidebars = {
    'student': StudSidebar,
    'adviser': AdviserSidebar,
    'faculty': FacultySidebar,
    'chair': ChairSidebar
  };
  return sidebars[props.userRole] || StudSidebar;
});

// Layout sizing constants
const sidebarWidth = 250;
const collapsedWidth = 60;

/**
 * Dynamic CSS Variables
 * Calculates the margin for the main content based on sidebar state
 */
const layoutStyles = computed(() => {
  const isSidebarVisible = layoutStore.layoutPreference !== 'navbar';
  const width = layoutStore.isSidebarCollapsed ? collapsedWidth : sidebarWidth;

  return {
    '--sidebar-width': isSidebarVisible ? `${width}px` : '0px',
    '--header-height': layoutStore.layoutPreference !== 'sidebar' ? '60px' : '0px'
  };
});
</script>

<style scoped>
.app {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.main-content {
  /* Automatically shifts content based on the calculated sidebar width */
  margin-left: var(--sidebar-width);
  margin-top: var(--header-height);
  flex: 1;
  transition: margin-left 0.3s ease;
}

.content-wrapper {
  max-width: 1600px;
  width: 100%;
  margin: 0 auto;
  padding: 2rem;
  box-sizing: border-box;
}
</style>