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

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useLayoutStore } from '@/stores/layout';
import { useUserStore } from '@/stores/user';
import { useThemeStore } from '@/stores/theme';
import { Icon as IconifyIcon } from '@iconify/vue';
import UserDropdownPopover from '@/components/ui/user_dropdown_popover.vue';

import AdminSidebar from './admin_sidebar.vue'; 
import AdviserSidebar from './adviser_sidebar.vue'; 
import FacultySidebar from './faculty_sidebar.vue';
import StudentSidebar from './student_sidebar.vue';

const router = useRouter();
const route = useRoute();
const layoutStore = useLayoutStore();
const userStore = useUserStore();
const themeStore = useThemeStore();
const userButtonRef = ref(null);
const isUserMenuOpen = ref(false);

const layoutMode = computed(() => layoutStore.layoutPreference);
const isCollapsed = computed(() => layoutStore.isSidebarCollapsed && !layoutStore.isMobileSidebarOpen);
const currentRole = computed(() => route.params.role || 'student');

const sidebarComponent = computed(() => {
  const roleComponentMap = {
    admin: AdminSidebar,
    adviser: AdviserSidebar,
    faculty: FacultySidebar,
    student: StudentSidebar
  };
  return roleComponentMap[currentRole.value] || StudentSidebar;
}); 

const themeButtonText = computed(() => {
  const currentTheme = themeStore.availableThemes.find(t => t.id === themeStore.currentTheme);
  return currentTheme?.name || 'Theme';
});

const toggleSidebar = () => {
  window.innerWidth <= 1024 ? layoutStore.toggleMobileSidebar() : layoutStore.toggleSidebar();
};

const toggleTheme = () => themeStore.toggleTheme();
const toggleUserMenu = (event) => { event.stopPropagation(); isUserMenuOpen.value = !isUserMenuOpen.value; };
const closeUserMenu = () => { isUserMenuOpen.value = false; };
const handleClickOutside = (event) => {
  if (isUserMenuOpen.value && userButtonRef.value && !userButtonRef.value.contains(event.target)) closeUserMenu();
};

onMounted(() => document.addEventListener('click', handleClickOutside));
onUnmounted(() => document.removeEventListener('click', handleClickOutside));
</script>

<style scoped>
.sidebar { display: flex; flex-direction: column; height: 100%; overflow: hidden; background-color: #fff; }
.sidebar-header { padding: 20px 20px 10px 20px; }
.sidebar-label-italic { font-family: 'Courier New', Courier, monospace; font-style: italic; font-size: 0.9rem; color: #333; letter-spacing: 1px; }
.sidebar-content { flex: 1; overflow-y: auto; padding: 0; }

/* CENTRALIZED CHILD STYLES */
:deep(.sidebar-inner-content) { font-family: 'Courier New', Courier, monospace; width: 100%; padding: 10px; }
:deep(.course-section) { margin-bottom: 5px; }
:deep(.course-header) { display: flex; align-items: center; padding: 8px 12px; cursor: pointer; }
:deep(.course-title) { margin: 0; color: #999; font-size: 1.5rem; font-weight: 800; letter-spacing: 2px; transition: color 0.3s; }
:deep(.course-title.title-active) { color: #800000; }
:deep(.chevron) { margin-right: 8px; color: #666; transition: transform 0.3s ease; display: flex; }
:deep(.chevron.rotated) { transform: rotate(90deg); }
:deep(.course-nav) { display: flex; flex-direction: column; padding-left: 15px; }
:deep(.nav-item-container) { position: relative; width: 100%; }
:deep(.guide-line) { position: absolute; left: 12px; top: -10px; bottom: 20px; width: 1px; background-color: #ddd; }
:deep(.nav-btn) { border: none; background: transparent; padding: 10px 16px; margin: 2px 0; cursor: pointer; font-family: inherit; width: 100%; text-align: left; transition: all 0.2s; border-radius: 6px; }
:deep(.parent-item) { font-weight: 700; font-size: 1rem; color: #555; }
:deep(.sub-item) { padding-left: 35px; font-size: 0.9rem; color: #777; font-style: italic; }
:deep(.btn-active) { color: #FFA500 !important; }
:deep(.parent-item.btn-active) { font-weight: 800; }
:deep(.sub-item.btn-active) { font-weight: 800; text-decoration: underline; text-underline-offset: 4px; background-color: #fff9f0; }
:deep(.nav-btn:hover:not(.btn-active)) { background-color: #f5f5f5; }
:deep(.slide-fade-enter-active), :deep(.slide-fade-leave-active) { transition: all 0.25s ease; }
:deep(.slide-fade-enter-from), :deep(.slide-fade-leave-to) { transform: translateY(-5px); opacity: 0; }
</style>