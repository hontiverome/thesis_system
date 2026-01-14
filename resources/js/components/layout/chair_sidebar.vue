<template>
  <aside class="sidebar-container">
    <div class="sidebar-label">{{ label }}</div>

    <div v-for="course in courses" :key="course.id" class="course-section">
      <button 
        class="course-header" 
        @click="handleHeaderClick(course)"
        :aria-expanded="openSections.includes(course.id)"
      >
        <h2 
          class="course-title" 
          :class="{ 'active-primary': modelValue === course.id }"
        >
          {{ course.title }}
        </h2>
        
        <div class="header-controls">
          <Icon 
            v-if="course.groups?.length" 
            icon="lucide:chevron-down" 
            class="arrow" 
            :class="{ 'is-open': openSections.includes(course.id) }"
          />
          <div class="vertical-divider" :class="{ active: modelValue === course.id }"></div>
        </div>
      </button>

      <transition name="slide">
        <div v-if="openSections.includes(course.id)" class="course-content">
          <div v-for="(group, gIndex) in course.groups" :key="`${course.id}-${gIndex}`" class="group-container">
            
            <button 
              v-if="group.header" 
              class="sub-header"
              :class="{ 'sub-header-active': activeSubHeader === `${course.id}-${group.header}` }"
              @click="handleSubHeaderClick(course.id, group.header)"
            >
              {{ group.header }}
            </button>

            <transition name="slide">
              <nav v-if="activeSubHeader === `${course.id}-${group.header}` && group.items?.length" class="pill-nav">
                <button 
                  v-for="item in group.items"
                  :key="item"
                  class="pill-item" 
                  :class="{ 'pill-active': activeSubItem === item && modelValue === course.id }"
                  @click.stop="setActiveItem(course.id, item)"
                >
                  {{ item }}
                </button>
              </nav>
            </transition>

            <transition name="slide">
              <nav v-if="activeSubHeader === `${course.id}-${group.header}` && group.links?.length" class="link-nav">
                <button 
                  v-for="link in group.links"
                  :key="link"
                  class="link-item"
                  :class="{ 'link-active': activeSubItem === link && modelValue === course.id }"
                  @click.stop="setActiveItem(course.id, link)"
                >
                  {{ link }}
                </button>
              </nav>
            </transition>
          </div>
        </div>
      </transition>
    </div>
  </aside>
</template>

<script setup>
import { ref } from 'vue';
import { Icon } from '@iconify/vue';

const props = defineProps({
  courses: { type: Array, required: true },
  label: { type: String, default: 'Courses' },
  modelValue: { type: String, default: '' }, 
  activeSubItem: { type: String, default: '' } 
});

const emit = defineEmits(['update:modelValue', 'update:activeSubItem', 'item-click']);

const openSections = ref([]);
const activeSubHeader = ref(''); // Format: "courseId-headerTitle"

const handleHeaderClick = (course) => {
  emit('update:modelValue', course.id);
  
  if (course.groups?.length) {
    const index = openSections.value.indexOf(course.id);
    if (index > -1) {
      openSections.value.splice(index, 1);
      activeSubHeader.value = ''; 
    } else {
      openSections.value.push(course.id);
    }
  }
};

const handleSubHeaderClick = (courseId, headerTitle) => {
  const combinedId = `${courseId}-${headerTitle}`;
  emit('update:modelValue', courseId);
  activeSubHeader.value = activeSubHeader.value === combinedId ? '' : combinedId;
};

const setActiveItem = (courseId, val) => {
  emit('update:modelValue', courseId);
  emit('update:activeSubItem', val);
  emit('item-click', { type: 'sub-item', courseId, val });
};
</script>

<style scoped>
/* Theme Variables for easy customization */
.sidebar-container {
  --sidebar-active-primary: #800000;
  --sidebar-accent: #f1a34b;
  --sidebar-text-muted: #999;
  --sidebar-transition: 0.3s ease-in-out;
}

.course-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  background: none;
  border: none;
  cursor: pointer;
  padding: 10px 0;
  text-align: left;
}

.sub-header {
  background: none;
  border: none;
  display: block;
  color: var(--sidebar-text-muted);
  text-decoration: underline;
  font-size: 0.9rem;
  font-weight: 600;
  margin: 15px 0 10px;
  cursor: pointer;
  transition: color 0.2s;
}

.sub-header-active {
  color: var(--sidebar-accent) !important;
}

.pill-active {
  background-color: var(--sidebar-active-primary) !important;
  color: #fff !important;
}

.active-primary {
  color: var(--sidebar-active-primary);
}

.slide-enter-active, .slide-leave-active {
  transition: all var(--sidebar-transition);
  max-height: 500px;
  overflow: hidden;
}
.slide-enter-from, .slide-leave-to {
  max-height: 0;
  opacity: 0;
}
</style>