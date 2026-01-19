<template>
  <div class="stud-sidebar-container">
    <div v-for="course in courses" :key="course.id" class="course-section">
      
      <div class="course-header" @click="toggleSection(course.id)">
        <span class="chevron" :class="{ rotated: openSections.includes(course.id) }">
          <IconifyIcon icon="mdi:play" width="10" height="10" />
        </span>

        <h2 class="course-title">{{ course.title }}</h2>
        
        <div class="vertical-bar" :class="{ active: openSections.includes(course.id) }"></div>
      </div>

      <transition name="slide-fade">
        <nav v-show="openSections.includes(course.id)" class="course-nav">
          <template v-for="(item, index) in course.items" :key="index">
            
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
      </transition>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Icon as IconifyIcon } from '@iconify/vue';

// --- DATA ---
const courses = ref([
  {
    id: 'MOR',
    title: 'MOR',
    items: [
      { type: 'label', text: 'Title Proposals' },
      { type: 'label', text: 'Chapters 1-3' },
      { type: 'link', text: 'DP1' },
      { type: 'link', text: 'DP2' }
    ]
  }
]);

// --- STATE ---
const openSections = ref(['MOR']); 
const activeCourseId = ref('MOR'); 
const activeSubItem = ref('DP1'); 

// --- ACTIONS ---
const toggleSection = (courseId) => {
  activeCourseId.value = courseId;
  if (openSections.value.includes(courseId)) {
    openSections.value = openSections.value.filter(id => id !== courseId);
  } else {
    openSections.value.push(courseId);
  }
};

const setActiveItem = (courseId, item) => {
  if (item.type === 'label') return; 
  activeCourseId.value = courseId;
  activeSubItem.value = item.text;
  // Emit event if parent needs to know (optional)
  // emit('navigate', item); 
};
</script>

<style scoped>
.stud-sidebar-container {
  font-family: 'Courier New', Courier, monospace;
  width: 100%;
}

.course-section {
  margin-bottom: 20px;
}

/* Header */
.course-header {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  cursor: pointer;
  padding: 10px 0;
}

.course-title {
  margin: 0;
  color: #800000;
  font-size: 1.8rem;
  font-weight: 800;
  letter-spacing: 2px;
  line-height: 1;
  text-align: center;
  user-select: none;
}

/* Indicators */
.chevron {
  position: absolute;
  left: 25px;
  color: #333;
  transition: transform 0.3s ease;
  display: flex;
  align-items: center;
}

.chevron.rotated {
  transform: rotate(90deg);
}

.vertical-bar {
  position: absolute;
  right: 25px;
  width: 3px;
  height: 28px;
  background-color: transparent;
  transition: background-color 0.3s ease;
}

.vertical-bar.active {
  background-color: #800000;
}

/* Navigation Items */
.course-nav {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 10px 0;
}

.nav-label {
  font-size: 0.75rem;
  font-weight: 700;
  color: #800000;
  background-color: #fcfcfc;
  padding: 6px 16px;
  border-radius: 12px;
  margin: 8px 0;
  letter-spacing: 0.5px;
}

.nav-btn {
  border: none;
  background: transparent;
  padding: 12px 0;
  font-weight: 700;
  font-size: 1.4rem;
  cursor: pointer;
  width: 100%;
  transition: all 0.2s ease;
  text-align: center;
  font-family: inherit;
  color: #444;
  letter-spacing: 1px;
}

.btn-active {
  color: #000;
  transform: scale(1.05);
}

.nav-btn:hover {
  color: #000;
}
</style>