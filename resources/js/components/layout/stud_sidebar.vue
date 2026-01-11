<template>
  <aside class="sidebar-container">
    <div class="sidebar-label">{{ label }}</div>

    <div v-for="course in courses" :key="course.id" class="course-section">
      <div 
        class="course-header" 
        @click="handleHeaderClick(course)"
      >
        <h2 class="course-title">{{ course.title }}</h2>
        
        <div class="header-controls">
          <span 
            v-if="course.items && course.items.length" 
            class="chevron" 
            :class="{ rotated: !openSections.includes(course.id) }"
          >
            <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
              <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"/>
            </svg>
          </span>
          
          <div 
            class="vertical-divider" 
            :class="{ active: modelValue === course.id }"
          ></div>
        </div>
      </div>

      <nav 
        v-if="course.items && course.items.length" 
        v-show="openSections.includes(course.id)" 
        class="course-nav"
      >
        <button 
          v-for="item in course.items"
          :key="item"
          class="nav-btn" 
          @click.stop="setActiveItem(course.id, item)"
          :class="getItemClass(course.id, item)"
        >
          {{ item }}
        </button>
      </nav>
    </div>
  </aside>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  // The list of courses and their sub-items
  courses: {
    type: Array,
    required: true,
    default: () => []
  },
  // The sidebar title (e.g., "Courses" or "Modules")
  label: {
    type: String,
    default: 'Courses'
  },
  // Controls which header has the red line (v-model)
  modelValue: {
    type: String,
    default: ''
  },
  // Controls which sub-item is active
  activeSubItem: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['update:modelValue', 'update:activeSubItem', 'item-click']);

// Tracks which sections are expanded (internal state)
const openSections = ref([]);

const handleHeaderClick = (course) => {
  emit('update:modelValue', course.id);

  if (course.items && course.items.length) {
    if (openSections.value.includes(course.id)) {
      openSections.value = openSections.value.filter(id => id !== course.id);
    } else {
      openSections.value.push(course.id);
    }
  }
  emit('item-click', { type: 'course', id: course.id });
};

const setActiveItem = (courseId, val) => {
  emit('update:modelValue', courseId);
  emit('update:activeSubItem', val);
  emit('item-click', { type: 'sub-item', courseId, val });
};

const getItemClass = (courseId, item) => {
  const isActive = props.activeSubItem === item && props.modelValue === courseId;
  return isActive ? 'btn-active' : 'btn-inactive';
};
</script>

<style scoped>
/* Keeping your existing styles exactly as they were */
.sidebar-container {
  width: 280px;
  height: 100vh;
  background-color: #fff;
  padding: 20px 0px 20px 20px;
  position: fixed;
  top: 80px; 
  left: 0;
  z-index: 100;
  box-sizing: border-box;
  font-family: sans-serif;
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
  margin-bottom: 20px;
  min-height: 40px;
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

.header-controls {
  position: absolute;
  right: 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.chevron {
  transition: transform 0.3s ease;
  color: #444;
  display: flex;
  align-items: center;
}

.chevron.rotated {
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

.course-nav {
  display: flex;
  flex-direction: column;
  gap: 10px;
  padding-right: 20px;
}

.nav-btn {
  border: none;
  border-radius: 8px;
  padding: 12px;
  font-weight: bold;
  font-size: 0.9rem;
  cursor: pointer;
  width: 100%;
  transition: all 0.2s ease;
  text-align: center;
}

.btn-active {
  background-color: #800000;
  color: white;
}

.btn-inactive {
  background-color: #f1f4f8;
  color: #000;
}

.nav-btn:hover {
  opacity: 0.9;
}
</style>