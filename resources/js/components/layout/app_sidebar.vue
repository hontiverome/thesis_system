<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { useLayoutStore } from '@/stores/layout';
import { useUserStore } from '@/stores/user';
import { useThemeStore } from '@/stores/theme';
import { Icon as IconifyIcon } from '@iconify/vue';
import UserDropdownPopover from '@/components/ui/user_dropdown_popover.vue';

const router = useRouter();
const layoutStore = useLayoutStore();
const userStore = useUserStore();
const themeStore = useThemeStore();
const userButtonRef = ref(null);
const layoutMode = computed(() => layoutStore.layoutPreference);
const isUserMenuOpen = ref(false);

const isCollapsed = computed(() => layoutStore.isSidebarCollapsed && !layoutStore.isMobileSidebarOpen);
const themeButtonText = computed(() => {
  const currentTheme = themeStore.availableThemes.find(t => t.id === themeStore.currentTheme);
  return currentTheme?.name || 'Theme';
});

// --- COURSE NAVIGATION LOGIC ---
const activeItem = ref('PROPOSAL');

const setActive = (itemName) => {
  activeItem.value = itemName;
  // Optional: router.push(...) logic here
};

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
      <h2 v-if="layoutStore.layoutPreference !== 'sidebar' || !isCollapsed" class="sidebar-title">
        {{ isCollapsed ? 'M' : 'Menu' }}
      </h2>
    </div>
    
    <div class="sidebar-content">
      
      <div class="course-card" v-if="!isCollapsed">
        <div class="course-title">MOR</div>
        
        <nav class="course-nav">
          <a href="#" 
             class="nav-item" 
             :class="{ active: activeItem === 'PROPOSAL' }"
             @click.prevent="setActive('PROPOSAL')">
            PROPOSAL
          </a>
          
          <a href="#" 
             class="nav-item" 
             :class="{ active: activeItem === 'CHAPTER 1' }"
             @click.prevent="setActive('CHAPTER 1')">
            CHAPTER 1
          </a>

          <a href="#" 
             class="nav-item" 
             :class="{ active: activeItem === 'CHAPTER 2' }"
             @click.prevent="setActive('CHAPTER 2')">
            CHAPTER 2
          </a>

          <a href="#" 
             class="nav-item" 
             :class="{ active: activeItem === 'CHAPTER 3' }"
             @click.prevent="setActive('CHAPTER 3')">
            CHAPTER 3
          </a>

          <a href="#" 
             class="nav-item" 
             :class="{ active: activeItem === 'OTHERS' }"
             @click.prevent="setActive('OTHERS')">
            OTHERS
          </a>
        </nav>
      </div>
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
}

.sidebar-content {
  flex: 1;
  overflow-y: auto;
  padding: 10px;
}

/* --- COURSE CARD STYLES --- */
.course-card {
  padding: 0;
  margin-bottom: 20px;
  background-color: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  border: 1px solid #e0e0e0;
  font-family: 'Courier New', Courier, monospace;
}

.course-title {
  text-align: center;
  font-size: 1.5rem;
  font-weight: bold;
  color: #800000;
  padding: 15px 0;
  background-color: white;
  letter-spacing: 2px;
  border-bottom: 1px solid #eee;
}

.course-nav {
  display: flex;
  flex-direction: column;
}

.nav-item {
  text-decoration: none;
  font-size: 0.85rem;
  font-weight: bold;
  text-transform: uppercase;
  padding: 12px 0;
  text-align: center;
  color: black;
  background-color: #f0f4f5;
  margin-bottom: 1px;
  transition: background-color 0.2s;
  cursor: pointer;
  display: block;
}

.nav-item.active {
  background-color: #800000;
  color: white;
  margin-bottom: 0;
}

.nav-item:hover:not(.active) {
  background-color: #e0e4e5;
}
</style>