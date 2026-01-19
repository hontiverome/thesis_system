<template>
  <div class="courses page-view" role="main" aria-labelledby="page-title">
    <h1 id="page-title" class="page-title">Academic Courses</h1>
    
    <p class="lead page-subtitle">
      Select a course below to view specific research methods and program details.
    </p>

    <BaseCard>
      <div class="courses-grid">
        <CourseCard 
          v-for="course in staticCourses" 
          :key="course.id" 
          :title="course.title" 
        />
      </div>
    </BaseCard>

    <div class="card theme-card mt-8">
      <h2>Current Theme: {{ currentThemeName }}</h2>
      <p>
        The UI elements on this page are optimized for the <strong>{{ currentThemeName }}</strong> theme.
      </p>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useThemeStore } from '@/stores/theme.js';
import BaseCard from '@/components/ui/core/BaseCard.vue';
import CourseCard from '@/components/ui/data/CourseCard.vue';

const themeStore = useThemeStore();

const currentThemeName = computed(() => {
  const theme = themeStore.availableThemes.find(t => t.id === themeStore.currentTheme);
  return theme ? theme.name : 'Default';
});

// Static data to ensure the page renders without API calls
const staticCourses = [
  { id: 1, title: 'Methods of Research' },
  { id: 2, title: 'Project Design 1' },
  { id: 3, title: 'Project Design 2' }
];
</script>

<style scoped>
.page-view {
  padding: 20px;
}

.page-title {
  font-size: 2.5rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

.page-subtitle {
  margin-bottom: 2rem;
  color: #666;
  font-size: 1.1rem;
}

/* Grid layout designed for the 300px width of your CourseCard wrapper */
.courses-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 40px;
  justify-items: center;
  padding: 20px 0;
}

.theme-card {
  padding: 20px;
  border-radius: 8px;
  border: 1px solid #e0e0e0;
  background-color: #f9f9f9;
}

.mt-8 {
  margin-top: 2rem;
}
</style>