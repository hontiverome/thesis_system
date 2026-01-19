<template>
  <div class="courses page-view" role="main">
    <h1 class="page-title">COURSES</h1>
    
    <BaseCard>
      
      <BaseCourseOverview subTitle="COURSE OVERVIEW"> 
        
        <div class="courses-grid">
          <CourseCard 
            v-for="course in staticCourses" 
            :key="course.id" 
            :title="course.title" 
          />
        </div>

      </BaseCourseOverview>
    </BaseCard>
  </div>
</template>

<script setup>
// Correct Imports based on your file paths
import BaseCard from '@/components/ui/core/BaseCard.vue';
import BaseCourseOverview from '@/components/ui/core/BaseCourseOverview.vue';
import CourseCard from '@/components/ui/data/CourseCard.vue';

/**
 * Static Course Data
 */
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
  margin-bottom: 1.5rem;
  color: #800000;
  text-transform: uppercase;
}

/* Grid layout designed for the 300px width of CourseCard */
.courses-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 40px;
  justify-items: center;
  padding: 20px 0;
}
</style>