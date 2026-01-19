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
import BaseCard from '@/components/ui/core/BaseCard.vue';
import BaseCourseOverview from '@/components/ui/core/BaseCourseOverview.vue';
import CourseCard from '@/components/ui/data/CourseCard.vue';

const staticCourses = [
  { id: 1, title: 'Methods of Research' },
  { id: 2, title: 'Project Design 1' },
  { id: 3, title: 'Project Design 2' }
];
</script>

<style scoped>
.page-view {
  padding: 50px; /* Increased padding for a more spacious look */
  background-color: #f4f6f9; /* The Gray background you requested */
  min-height: 100vh; /* Ensures the background covers the full screen height */
  width: 100%;
}

.page-title {
  font-size: 2.5rem;
  font-weight: bold;
  margin-bottom: 1.5rem;
  color: #800000; /* Maroon color */
  text-transform: uppercase;
}

/* Grid layout expands to the maximum width of the BaseCard */
.courses-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 40px;
  justify-items: center;
  padding: 20px 0;
}
</style>