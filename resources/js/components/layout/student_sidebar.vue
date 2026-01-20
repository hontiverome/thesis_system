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
              :class="(activeSubItem === item.text || currentRouteTab === item.text) && activeCourseId === course.id ? 'btn-active' : 'btn-inactive'"
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
import { ref, onMounted, computed } from 'vue';
import { useRoute } from 'vue-router';
import { useRouter } from 'vue-router';
import { Icon as IconifyIcon } from '@iconify/vue';

// --- IMPORTS ---
const route = useRoute();
const router = useRouter();

// --- DATA - STUDENT COURSES ---
const courses = ref([
  {
    id: 'MOR',
    title: 'MOR',
    items: [
      { type: 'link', text: 'Title Proposals', courseCode: 'mor' },
      { type: 'link', text: 'Chapters 1-3', courseCode: 'mor' }
    ]
  },
  {
    id: 'DP1',
    title: 'DP1',
    items: [
      { type: 'link', text: 'Revised Chapters 1-3', courseCode: 'dp1' },
      { type: 'link', text: 'Evaluation', courseCode: 'dp1' }
    ]
  },
  {
    id: 'DP2',
    title: 'DP2',
    items: [
      { type: 'link', text: 'Research/Thesis', courseCode: 'dp2' },
      { type: 'link', text: 'Documents', courseCode: 'dp2' },
      { type: 'link', text: 'Evaluation', courseCode: 'dp2' }
    ]
  }
]);

// --- COMPUTED ---
// Update activeSubItem based on current route
const currentRouteTab = computed(() => {
  const tabPath = route.params[0];
  if (!tabPath) return '';
  return tabPath
    .split('-')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ');
});

// Map route courses to sidebar course IDs
const courseRouteMap = {
  'mor': 'MOR',
  'dp1': 'DP1',
  'dp2': 'DP2'
};

// Get the current course from the route
const getCurrentActiveCourse = () => {
  const coursePath = route.params.course;
  return courseRouteMap[coursePath] || 'MOR';
};

// --- STATE ---
const openSections = ref([]);
const activeCourseId = ref('MOR');
const activeSubItem = ref('');

onMounted(() => {
  const activeCourse = getCurrentActiveCourse();
  activeCourseId.value = activeCourse;
  openSections.value = [activeCourse];
  
  const course = courses.value.find(c => c.id === activeCourse);
  if (course && course.items.length > 0) {
    activeSubItem.value = course.items[0].text;
  }
});

// --- ACTIONS ---
const toggleSection = (courseId) => {
  activeCourseId.value = courseId;
  if (openSections.value.includes(courseId)) {
    openSections.value = openSections.value.filter(id => id !== courseId);
  } else {
    openSections.value = [courseId];
    
    // Navigate to the first item of the newly opened course
    const course = courses.value.find(c => c.id === courseId);
    if (course && course.items.length > 0) {
      const firstItem = course.items[0];
      activeSubItem.value = firstItem.text;
      
      // Navigate to the first item's route
      if (firstItem.courseCode) {
        const tabName = firstItem.text.toLowerCase().replace(/\s+/g, '-');
        const role = route.params.role || 'student';
        
        router.push({
          name: `${role}-${firstItem.courseCode}-${tabName}`,
          params: { 
            role: role,
            0: tabName
          }
        });
      }
    }
  }
};

const setActiveItem = (courseId, item) => {
  if (item.type === 'label') return; 
  activeCourseId.value = courseId;
  activeSubItem.value = item.text;
  
  if (item.courseCode) {
    const tabName = item.text.toLowerCase().replace(/\s+/g, '-');
    const role = route.params.role || 'student';
    
    router.push({
      name: `${role}-${item.courseCode}-${tabName}`,
      params: { 
        role: role,
        0: tabName
      }
    });
  }
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
  padding: 8px 0;
  font-weight: 600;
  font-size: 0.95rem;
  cursor: pointer;
  width: 100%;
  transition: all 0.2s ease;
  text-align: center;
  font-family: inherit;
  color: #444;
  letter-spacing: 0.5px;
}

.btn-active {
  color: #FFA500;
  transform: scale(1.05);
  border-bottom: 2px solid #FFA500;
  padding-bottom: 6px;
}

.nav-btn:hover {
  color: #FFA500;
}

.slide-fade-enter-active {
  transition: all 0.3s ease;
}

.slide-fade-leave-active {
  transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from {
  transform: translateX(10px);
  opacity: 0;
}

.slide-fade-leave-to {
  transform: translateX(10px);
  opacity: 0;
}
</style>
