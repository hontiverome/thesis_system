<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useLayoutStore } from '@/stores/layout';
import { useUserStore } from '@/stores/user';
import { useThemeStore } from '@/stores/theme';
import { Icon as IconifyIcon } from '@iconify/vue';
import UserDropdownPopover from '@/components/ui/user_dropdown_popover.vue';

// IMPORT ROLE-SPECIFIC SIDEBARS
import AdminSidebar from './admin_sidebar.vue'; 
import StudentSidebar from './student_sidebar.vue'; 
import AdviserSidebar from './adviser_sidebar.vue'; 
import FacultySidebar from './faculty_sidebar.vue'; 

const router = useRouter();
const route = useRoute();
const layoutStore = useLayoutStore();
const userStore = useUserStore();
const themeStore = useThemeStore();
const userButtonRef = ref(null);
const layoutMode = computed(() => layoutStore.layoutPreference);
const isUserMenuOpen = ref(false);

// Get current role from route
const currentRole = computed(() => route.params.role || 'student');

// Select sidebar component based on role
const sidebarComponent = computed(() => {
  const roleComponentMap = {
    admin: AdminSidebar,
    student: StudentSidebar,
    adviser: AdviserSidebar,
    faculty: FacultySidebar
  };
  return roleComponentMap[currentRole.value] || StudentSidebar;
}); 

const isCollapsed = computed(() => layoutStore.isSidebarCollapsed && !layoutStore.isMobileSidebarOpen);
const themeButtonText = computed(() => {
  const currentTheme = themeStore.availableThemes.find(t => t.id === themeStore.currentTheme);
  return currentTheme?.name || 'Theme';
});

// --- LAYOUT LOGIC ---
const toggleSidebar = () => {
  const isSmall = window.innerWidth <= 1024;
  if (isSmall) {
    layoutStore.toggleMobileSidebar();
  } else {
    layoutStore.toggleSidebar();
  }
};

const toggleTheme = () => themeStore.toggleTheme();

const toggleUserMenu = (event) => {
  event.stopPropagation();
  isUserMenuOpen.value = !isUserMenuOpen.value;
};

const closeUserMenu = () => {
  isUserMenuOpen.value = false;
};

const handleClickOutside = (event) => {
  if (isUserMenuOpen.value && userButtonRef.value && !userButtonRef.value.contains(event.target)) {
    closeUserMenu();
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
  <aside class="sidebar" :data-layout-mode="layoutMode" :class="{ 'collapsed': isCollapsed }">
    
    <div class="sidebar-header">
      <button v-if="layoutStore.layoutPreference === 'sidebar'" @click="toggleSidebar" class="hamburger-button">
        <IconifyIcon icon="mdi:menu" class="hamburger-icon" />
      </button>
      
      <div v-if="!isCollapsed" class="header-label-container">
        <span class="sidebar-label-italic">Courses</span>
      </div>
    </div>
    
    <div class="sidebar-content">
      
      <component v-if="!isCollapsed" :is="sidebarComponent" />

    </div>

    <div class="sidebar-footer" v-if="layoutStore.layoutPreference !== 'both'">
      <button @click="toggleTheme" class="theme-toggle">
        <span class="icon"><IconifyIcon icon="mdi:palette" width="20" height="20" /></span>
        <span class="theme-toggle-text" v-if="!isCollapsed">{{ themeButtonText }}</span>
      </button>
      
      <button class="sidebar-user-button" ref="userButtonRef" @click.stop="toggleUserMenu">
          <div class="sidebar-avatar-container">
            <div class="sidebar-avatar-initials">{{ userStore.user?.firstName?.charAt(0) || 'U' }}</div>
          </div>
          <div class="user-info" v-if="!isCollapsed">
            <div class="sidebar-username">{{ userStore.user?.firstName || 'User' }}</div>
          </div>
          <IconifyIcon v-if="!isCollapsed" icon="mdi:chevron-down" class="sidebar-dropdown-arrow" />
      </button>
      <Teleport to="body">
          <UserDropdownPopover :is-open="isUserMenuOpen" :target-element="userButtonRef" :is-sidebar-collapsed="isCollapsed" @close="closeUserMenu"/>
      </Teleport>
    </div>
  </aside>
</template>

<style scoped>
/* Base Sidebar Layout */
.sidebar {
  display: flex;
  flex-direction: column;
  height: 100%;
  overflow: hidden;
  background-color: #fff;
}

.sidebar-header {
  padding: 20px 20px 10px 20px;
}

.sidebar-label-italic {
  font-family: 'Courier New', Courier, monospace;
  font-style: italic;
  font-size: 0.9rem;
  color: #333;
  letter-spacing: 1px;
}

.sidebar-content {
  flex: 1;
  overflow-y: auto;
  padding: 0;
}
</style>