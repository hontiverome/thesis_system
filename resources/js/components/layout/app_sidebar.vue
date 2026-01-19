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

// --- DYNAMIC COURSE DATA ---
// Updated to support "Labels" inside the list (like 'Title Proposals')
const courses = ref([
  {
    id: 'MOR',
    title: 'MOR',
    // Items can be objects with 'type' to distinguish between Headers and Links
    items: [
      { type: 'label', text: 'Title Proposals' },
      { type: 'link', text: 'PROPOSAL' },
      { type: 'label', text: 'Chapters 1-3' },
      { type: 'link', text: 'CHAPTER 1' },
      { type: 'link', text: 'CHAPTER 2' },
      { type: 'link', text: 'CHAPTER 3' },
      { type: 'link', text: 'OTHERS' }
    ]
  }
]);

// --- NAVIGATION STATE ---
const openSections = ref(['MOR']); 
const activeCourseId = ref('MOR'); 
const activeSubItem = ref('PROPOSAL');

const toggleSection = (courseId) => {
  activeCourseId.value = courseId;
  if (openSections.value.includes(courseId)) {
    openSections.value = openSections.value.filter(id => id !== courseId);
  } else {
    openSections.value.push(courseId);
  }
};

const setActiveItem = (courseId, item) => {
  if (item.type === 'label') return; // Labels aren't clickable
  activeCourseId.value = courseId;
  activeSubItem.value = item.text;
  // router.push(...)
};

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
      <div v-if="layoutStore.layoutPreference !== 'sidebar' || !isCollapsed" class="header-text-container">
        <h2 class="sidebar-title">Courses</h2>
      </div>
    </div>
    
    <div class="sidebar-content">
      
      <div v-if="!isCollapsed" class="courses-container">
        
        <div v-for="course in courses" :key="course.id" class="course-section">
          
          <div class="course-header" @click="toggleSection(course.id)">
            
            <span v-if="course.items && course.items.length" 
                  class="chevron" 
                  :class="{ rotated: openSections.includes(course.id) }">
              <IconifyIcon icon="mdi:play" width="12" height="12" />
            </span>

            <h2 class="course-title">{{ course.title }}</h2>
            
            <div class="vertical-bar" 
                 :class="{ active: openSections.includes(course.id) }">
            </div>
          </div>

          <nav v-if="course.items && course.items.length" 
               v-show="openSections.includes(course.id)" 
               class="course-nav">
            
            <template v-for="item in course.items" :key="item.text">
              
              <div v-if="item.type === 'label'" class="nav-label">
                {{ item.text }}
              </div>

              <button
                v-else
                class="nav-btn"
                :class="activeSubItem === item.text && activeCourseId === course.id ? 'btn-active' : 'btn-inactive'"
                @click.stop="setActiveItem(course.id, item)"
              >
                {{ item.text }}
              </button>

            </template>

          </nav>

        </div>

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
  background-color: #fff;
}

.sidebar-header {
  padding: 20px 20px 10px 20px;
}

.sidebar-title {
  font-family: 'Courier New', Courier, monospace;
  font-style: italic;
  font-size: 1rem;
  color: #333;
  margin: 0;
}

.sidebar-content {
  flex: 1;
  overflow-y: auto;
  padding: 10px 0; /* Remove horizontal padding to let hover effects span width */
}

/* --- COURSE SECTION STYLES --- */
.courses-container {
  font-family: 'Courier New', Courier, monospace;
}

.course-section {
  margin-bottom: 20px;
  background-color: white;
}

.course-header {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center; /* Center the Title */
  width: 100%;
  cursor: pointer;
  padding: 10px 0;
}

/* Header Title (MOR) */
.course-title {
  margin: 0;
  color: #800000;
  font-size: 2rem;
  font-weight: 800;
  letter-spacing: 2px;
  line-height: 1;
  text-align: center;
  user-select: none;
}

/* Left Chevron */
.chevron {
  position: absolute;
  left: 25px; /* Position on left like screenshot */
  transition: transform 0.3s ease;
  color: #333;
  display: flex;
  align-items: center;
}

.chevron.rotated {
  transform: rotate(90deg); /* Rotate down */
}

/* Right Vertical Bar */
.vertical-bar {
  position: absolute;
  right: 25px;
  width: 4px;
  height: 30px;
  background-color: transparent;
  transition: background-color 0.3s ease;
}

.vertical-bar.active {
  background-color: #800000; /* Red bar when active */
}

.course-nav {
  display: flex;
  flex-direction: column;
  padding: 10px 0;
}

/* Labels (e.g., "Title Proposals") */
.nav-label {
  text-align: center;
  font-size: 0.75rem;
  font-weight: bold;
  color: #800000;
  background-color: #fafafa; /* Slight highlight for label area */
  padding: 5px 0;
  margin: 10px 0 5px 0;
  border-radius: 4px;
  width: 60%;
  margin-left: auto;
  margin-right: auto;
}

/* Links (e.g., "DP1") */
.nav-btn {
  border: none;
  background: transparent;
  padding: 10px 0;
  font-weight: 900; /* Extra bold */
  font-size: 1.2rem;
  cursor: pointer;
  width: 100%;
  transition: all 0.2s ease;
  text-align: center;
  font-family: inherit;
  color: #333;
}

.btn-active {
  color: #800000; /* Active text is red */
}

.nav-btn:hover {
  background-color: rgba(0,0,0,0.03); /* Subtle hover */
}
</style>