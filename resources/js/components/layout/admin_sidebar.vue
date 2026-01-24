<template>
  <div class="stud-sidebar-container">
    <div v-for="course in courses" :key="course.id" class="course-section">
      
      <div class="course-header" @click="toggleSection(course.id)">
        <span class="chevron" :class="{ rotated: openSections.includes(course.id) }">
          <IconifyIcon icon="mdi:chevron-right" width="20" height="20" />
        </span>
        <h2 class="course-title" :class="{ 'title-active': activeCourseId === course.id }">
          {{ course.title }}
        </h2>
      </div>

      <transition name="slide-fade">
        <nav v-show="openSections.includes(course.id)" class="course-nav">
          <template v-for="item in course.items" :key="item.id">
            
            <div class="nav-item-container">
              <div v-if="item.isSubItem" class="guide-line"></div>

              <button
                class="nav-btn"
                :class="[
                  isItemActive(item) && activeCourseId === course.id ? 'btn-active' : 'btn-inactive',
                  item.isSubItem ? 'sub-item' : 'parent-item'
                ]"
                @click.stop="setActiveItem(course.id, item)"
              >
                {{ item.text }}
              </button>
            </div>

          </template>
        </nav>
      </transition>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { Icon as IconifyIcon } from '@iconify/vue';

const route = useRoute();
const router = useRouter();

// --- DATA: Unique IDs for distinct navigation ---
const courses = ref([
  {
    id: 'MOR',
    title: 'MOR',
    items: [
      { id: 'mor-prop', text: 'Title Proposals', courseCode: 'mor' },
      { id: 'mor-prop-sec', text: 'Sections', courseCode: 'mor', isSubItem: true, parentId: 'mor-prop' },
      { id: 'mor-chap', text: 'Chapters 1-3', courseCode: 'mor' },
      { id: 'mor-chap-sec', text: 'Sections', courseCode: 'mor', isSubItem: true, parentId: 'mor-chap' },
      { id: 'mor-status', text: 'Panel Status', courseCode: 'mor' },
      { id: 'mor-faculty', text: 'Faculty', courseCode: 'mor' }
    ]
  },
  {
    id: 'DP1',
    title: 'DP1',
    items: [
      { id: 'dp1-revised', text: 'Revised Chapters 1-3', courseCode: 'dp1' },
      { id: 'dp1-revised-sec', text: 'Sections', courseCode: 'dp1', isSubItem: true, parentId: 'dp1-revised' }
    ]
  },
  {
    id: 'DP2',
    title: 'DP2',
    items: [
      { id: 'dp2-thesis', text: 'Research/Thesis', courseCode: 'dp2' },
      { id: 'dp2-thesis-sec', text: 'Sections', courseCode: 'dp2', isSubItem: true, parentId: 'dp2-thesis' },
      { id: 'dp2-faculty', text: 'Faculty', courseCode: 'dp2' }
    ]
  }
]);

const openSections = ref([]);
const activeCourseId = ref('MOR');
const activeItemId = ref('');

// --- LOGIC: Checks if item itself or its child is active ---
const isItemActive = (item) => {
  if (activeItemId.value === item.id) return true;
  const activeItemData = courses.value.flatMap(c => c.items).find(i => i.id === activeItemId.value);
  return activeItemData?.parentId === item.id;
};

const syncState = () => {
  const coursePath = route.params.course;
  const tabPath = route.params[0];
  if (coursePath) {
    const courseId = coursePath.toUpperCase();
    activeCourseId.value = courseId;
    if (!openSections.value.includes(courseId)) openSections.value = [courseId];
    const course = courses.value.find(c => c.id === courseId);
    if (course && tabPath) {
       const match = course.items.find(item => item.text.toLowerCase().replace(/\s+/g, '-') === tabPath);
       if (match) activeItemId.value = match.id;
    }
  }
};

onMounted(syncState);
watch(() => route.path, syncState);

const toggleSection = (courseId) => {
  activeCourseId.value = courseId;
  openSections.value = openSections.value.includes(courseId) ? openSections.value.filter(id => id !== courseId) : [courseId];
};

const setActiveItem = (courseId, item) => {
  activeCourseId.value = courseId;
  activeItemId.value = item.id;
  const tabSlug = item.text.toLowerCase().replace(/\s+/g, '-');
  const role = route.params.role || 'admin';
  router.push({ name: `${role}-${item.courseCode}-${tabSlug}`, params: { role, 0: tabSlug } });
};
</script>

<style scoped>
.stud-sidebar-container {
  font-family: 'Courier New', Courier, monospace;
  width: 100%;
  padding: 10px;
}

.course-section { margin-bottom: 5px; }
.course-header { display: flex; align-items: center; padding: 8px 12px; cursor: pointer; }

/* Top-level Header (MOR, DP1, DP2) remains Red */
.course-title {
  margin: 0;
  color: #999;
  font-size: 1.5rem;
  font-weight: 800;
  letter-spacing: 2px;
  transition: color 0.3s;
}
.course-title.title-active { color: #800000; }

.chevron { margin-right: 8px; color: #666; transition: transform 0.3s ease; display: flex; }
.chevron.rotated { transform: rotate(90deg); }

/* Tree Navigation Styling */
.course-nav { display: flex; flex-direction: column; padding-left: 15px; }
.nav-item-container { position: relative; width: 100%; }

.guide-line {
  position: absolute;
  left: 12px;
  top: -10px;
  bottom: 20px;
  width: 1px;
  background-color: #ddd;
}

.nav-btn {
  border: none;
  background: transparent;
  padding: 10px 16px;
  margin: 2px 0;
  cursor: pointer;
  font-family: inherit;
  width: 100%;
  text-align: left;
  transition: all 0.2s;
  border-radius: 6px;
}

/* Base Styles */
.parent-item { font-weight: 700; font-size: 1rem; color: #555; }
.sub-item {
  padding-left: 35px;
  font-size: 0.9rem;
  color: #777;
  font-style: italic;
}

/* UNIFIED ORANGE ACTIVE STATE */
.btn-active {
  color: #FFA500 !important; /* All nav items turn orange when active */
}

/* Specific styling for parent active state */
.parent-item.btn-active {
  font-weight: 800;
}

/* Specific styling for sub-item active state */
.sub-item.btn-active {
  font-weight: 800;
  text-decoration: underline;
  text-underline-offset: 4px;
  background-color: #fff9f0; /* Soft highlight for sub-item */
}

.nav-btn:hover:not(.btn-active) {
  background-color: #f5f5f5;
}

.slide-fade-enter-active, .slide-fade-leave-active { transition: all 0.25s ease; }
.slide-fade-enter-from, .slide-fade-leave-to { transform: translateY(-5px); opacity: 0; }
</style>