<template>
  <aside class="sidebar-container">
    <div class="sidebar-label">{{ label }}</div>

    <div v-for="course in courses" :key="course.id" class="course-section">
      <div class="course-header" @click="handleHeaderClick(course)">
        <span 
          v-if="course.groups && course.groups.length" 
          class="chevron-left" 
          :class="{ rotated: openSections.includes(course.id) }"
        >
          <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
            <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
          </svg>
        </span>

        <h2 class="course-title" :class="{ 'is-active-course': modelValue === course.id }">
          {{ course.title }}
        </h2>
        
        <div class="header-controls">
          <div 
            class="vertical-divider" 
            :class="{ active: modelValue === course.id }"
          ></div>
        </div>
      </div>

      <nav 
        v-if="course.groups" 
        v-show="openSections.includes(course.id)" 
        class="course-nav"
      >
        <div v-for="group in course.groups" :key="group.heading" class="nav-group">
          <button 
            class="nav-item heading" 
            :class="{ 'is-active': activeSubItem === group.heading && modelValue === course.id }"
            @click.stop="setActiveItem(course.id, group.heading)"
          >
            {{ group.heading }}
          </button>

          <div v-if="group.subItems && group.subItems.length" class="sub-items-container">
            <button 
              v-for="sub in group.subItems" 
              :key="sub"
              class="nav-item sub-subheading"
              :class="{ 'is-active': activeSubItem === sub && modelValue === course.id }"
              @click.stop="setActiveItem(course.id, sub)"
            >
              {{ sub }}
            </button>
          </div>
        </div>
      </nav>
    </div>
  </aside>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  courses: { type: Array, required: true },
  label: { type: String, default: 'Courses' },
  modelValue: { type: String, default: 'MOR' },
  activeSubItem: { type: String, default: 'Title Proposals' }
});

const emit = defineEmits(['update:modelValue', 'update:activeSubItem', 'item-click']);

// Accordion state
const openSections = ref([props.modelValue]);

const handleHeaderClick = (course) => {
  // Transfer Red color to the new clicked course
  emit('update:modelValue', course.id);
  
  // Toggle Accordion
  const index = openSections.value.indexOf(course.id);
  if (index > -1) {
    openSections.value.splice(index, 1);
  } else {
    openSections.value.push(course.id);
  }
  
  emit('item-click', { type: 'course', id: course.id });
};

const setActiveItem = (courseId, val) => {
  // Transfer Red color to the course containing this item
  emit('update:modelValue', courseId);
  // Transfer Orange color/Underline to this specific item
  emit('update:activeSubItem', val);
  
  emit('item-click', { type: 'item', courseId, val });
};
</script>

<style scoped>
/* ALL ORIGINAL STYLES PRESERVED BELOW */
.sidebar-container {
  width: 280px;
  height: 100vh;
  background-color: #fff;
  padding: 20px;
  position: fixed;
  top: 80px; 
  left: 0;
  z-index: 100;
  box-sizing: border-box;
  font-family: 'Courier New', Courier, monospace;
  overflow-y: auto;
  border-right: 1px solid #eee;
}

.sidebar-label {
  text-align: left;
  font-size: 1.1rem;
  font-style: italic;
  margin-bottom: 25px;
  color: #333;
}

.course-section {
  margin-bottom: 30px;
}

.course-header {
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;
  cursor: pointer;
  margin-bottom: 15px;
}

.course-title {
  margin: 0 0 0 30px;
  color: #999; 
  font-size: 2.2rem;
  font-weight: 800;
  letter-spacing: 2px;
  user-select: none;
  transition: color 0.3s ease;
}

.course-title.is-active-course {
  color: #800000;
}

.chevron-left {
  position: absolute;
  left: 0;
  transition: transform 0.3s ease;
  color: #000;
}

.chevron-left.rotated {
  transform: rotate(90deg);
}

.header-controls {
  position: absolute;
  right: 0;
}

.vertical-divider {
  width: 4px;
  height: 35px;
  background-color: transparent;
  transition: background-color 0.3s ease;
}

.vertical-divider.active {
  background-color: #800000;
}

.course-nav {
  display: flex;
  flex-direction: column;
  gap: 15px;
  padding-left: 30px;
}

.nav-group {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 10px;
}

.sub-items-container {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 10px;
  padding-left: 20px;
}

.nav-item {
  background: none;
  border: none;
  font-family: inherit;
  font-size: 1.1rem;
  color: #999;
  cursor: pointer;
  transition: color 0.2s ease;
  text-align: left;
  padding: 0;
}

.nav-item.sub-subheading {
  font-size: 1rem;
}

.nav-item.is-active {
  color: #E48217; 
  font-weight: bold;
}

.nav-item.heading.is-active {
  text-decoration: underline;
  text-underline-offset: 4px;
}

.nav-item:hover {
  opacity: 0.8;
}
</style>