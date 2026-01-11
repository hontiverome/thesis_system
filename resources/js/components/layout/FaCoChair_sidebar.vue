<template>
  <aside class="sidebar-container">
    <div class="sidebar-label">{{ label }}</div>

    <div v-for="course in courses" :key="course.id" class="course-section">
      <div 
        class="course-header" 
        @click="handleHeaderClick(course)"
      >
        <h2 
          class="course-title" 
          :class="{ 'active-red-text': modelValue === course.id }"
        >
          {{ course.title }}
        </h2>
        
        <div class="header-controls">
          <span 
            v-if="course.items && course.items.length" 
            class="arrow" 
            :class="{ 'is-open': openSections.includes(course.id) }"
          >
            ▼
          </span>
          
          <div 
            class="vertical-divider" 
            :class="{ active: modelValue === course.id }"
          ></div>
        </div>
      </div>

      <transition name="slide">
        <nav 
          v-if="course.items && course.items.length && openSections.includes(course.id)" 
          class="course-nav"
        >
          <button 
            v-for="item in course.items"
            :key="item"
            class="pill-item" 
            @click.stop="setActiveItem(course.id, item)"
            :class="{ 'pill-active': activeSubItem === item && modelValue === course.id }"
          >
            {{ item }}
          </button>
        </nav>
      </transition>
    </div>
  </aside>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  courses: { type: Array, required: true, default: () => [] },
  label: { type: String, default: 'Courses' },
  modelValue: { type: String, default: '' },
  activeSubItem: { type: String, default: '' }
});

const emit = defineEmits(['update:modelValue', 'update:activeSubItem', 'item-click']);

const openSections = ref([]);

const handleHeaderClick = (course) => {
  emit('update:modelValue', course.id);

  if (course.items && course.items.length) {
    if (openSections.value.includes(course.id)) {
      openSections.value = openSections.value.filter(id => id !== course.id);
    } else {
      openSections.value.push(course.id);
    }
  } else {
    emit('update:activeSubItem', '');
  }
  emit('item-click', { type: 'course', id: course.id });
};

const setActiveItem = (courseId, val) => {
  emit('update:modelValue', courseId);
  emit('update:activeSubItem', val);
  emit('item-click', { type: 'sub-item', courseId, val });
};
</script>

<style scoped>
.sidebar-container {
  position: fixed;
  top: 80px; /* Aligned with your second template */
  left: 0;
  width: 280px;
  height: 100vh;
  background-color: #fff;
  padding: 20px 0px 20px 20px; /* Copied from second template */
  z-index: 100;
  box-sizing: border-box;
  font-family: sans-serif; /* Updated to sans-serif */
  overflow-y: auto;
  border-right: 1px solid #eee;
}

.sidebar-label {
  text-align: left;
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: 25px;
  color: #333;
}

.course-section {
  margin-bottom: 30px;
}

.course-header {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  cursor: pointer;
  min-height: 40px; /* Height constraint from second template */
}

.course-title {
  margin: 0;
  color: #444; /* Default color from first template */
  font-size: 1.8rem;
  font-weight: 800;
  letter-spacing: 2px;
  line-height: 1;
  text-align: center;
  transition: color 0.3s ease;
}

.active-red-text {
  color: #800000 !important;
}

.header-controls {
  position: absolute;
  right: 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.arrow { 
  font-size: 0.7rem; 
  color: #d1d1d1; 
  transition: transform 0.3s ease; 
}
.arrow.is-open { 
  transform: rotate(180deg); 
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

/* Sub-items (Pills) */
.course-nav {
  display: flex;
  flex-direction: column;
  gap: 10px;
  padding-right: 20px;
  margin-top: 15px;
}

.pill-item {
  border: none;
  background-color: #f4f7f9;
  padding: 12px;
  border-radius: 8px;
  font-weight: bold;
  font-size: 0.9rem;
  font-family: inherit;
  color: #333;
  text-align: center;
  cursor: pointer;
  transition: all 0.2s ease;
  width: 100%;
}

.pill-active {
  background-color: #800000 !important;
  color: #ffffff !important;
}

/* Slide Transition */
.slide-enter-active, .slide-leave-active {
  transition: all 0.3s ease;
  max-height: 500px;
  overflow: hidden;
}
.slide-enter-from, .slide-leave-to {
  max-height: 0;
  opacity: 0;
}
</style>