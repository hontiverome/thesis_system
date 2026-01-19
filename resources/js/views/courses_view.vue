<template>
  <div class="courses page-view" role="main">

    <BaseCard v-if="isAuthenticated && user" title="COURSE">
      <BaseCourseOverview :subTitle="displaySubTitle"> 
        
        <div class="courses-grid">
          <CourseCard 
            v-for="course in staticCourses" 
            :key="course.id" 
            :title="course.title" 
          />
        </div>

        <div class="interface-container">
          
          <div v-if="isAdminPath && isAdmin" class="role-workspace admin-bg">
            <h3 class="role-label">System Administrator Panel</h3>
            <div class="action-row">
              <button class="pup-btn">Manage Curriculum</button>
              <button class="pup-btn">View System Logs</button>
            </div>
          </div>

          <div v-else-if="isFacultyPath && (isFaculty || isAdviser)" class="role-workspace faculty-bg">
            <h3 class="role-label">Faculty Workspace</h3>
            <div class="action-row">
              <button class="pup-btn">My Advising Load</button>
              <button class="pup-btn">Grade Submissions</button>
            </div>
          </div>

          <div v-else-if="isStudentPath && isStudent" class="role-workspace student-bg">
            <h3 class="role-label">Student Portal</h3>
            <p>Welcome back, {{ user.first_name }}! Track your course progress below.</p>
            <button class="pup-btn">View My Progress</button>
          </div>

        </div>
      </BaseCourseOverview>
    </BaseCard>

    <div v-else-if="loading" class="loading-state">
      <p>Loading course content...</p>
    </div>

    <div v-else class="error-state">
      <p>Please log in to view courses.</p>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import { useAuth } from '@/composables/useAuth';
import BaseCard from '@/components/ui/core/BaseCard.vue';
import BaseCourseOverview from '@/components/ui/core/BaseCourseOverview.vue';
import CourseCard from '@/components/ui/data/CourseCard.vue';

// Kinukuha ang values mula sa iyong useAuth composable
const route = useRoute();
const { 
  user, 
  isAdmin, 
  isStudent, 
  isFaculty, 
  isAdviser, 
  isAuthenticated, 
  loading 
} = useAuth();

/**
 * Path detection logic: 
 * Chine-check kung ang kasalukuyang URL ay para sa aling role
 */
const isAdminPath = computed(() => route.path.startsWith('/admin'));
const isFacultyPath = computed(() => route.path.startsWith('/faculty'));
const isStudentPath = computed(() => route.path.startsWith('/student'));

/**
 * Dynamic Title Logic: Nagbabago base sa URL path
 */
const displaySubTitle = computed(() => {
  if (isAdminPath.value) return 'ALL COURSES - ADMIN VIEW';
  if (isFacultyPath.value) return 'FACULTY: COURSE ASSIGNMENTS';
  if (isStudentPath.value) return 'STUDENT: COURSE OVERVIEW';
  return 'COURSE OVERVIEW';
});

const staticCourses = [
  { id: 1, title: 'Methods of Research' },
  { id: 2, title: 'Project Design 1' },
  { id: 3, title: 'Project Design 2' }
];
</script>

<style scoped>
.page-view {
  padding: 50px;
  background-color: #f4f6f9; /* Gray background */
  min-height: 100vh;
  width: 100%;
}

.page-title {
  font-size: 2.5rem;
  font-weight: bold;
  margin-bottom: 1.5rem;
  color: #800000; /* Maroon */
  text-transform: uppercase;
}

.courses-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 40px;
  justify-items: center;
  padding: 20px 0;
}

/* Interface Layers */
.role-workspace {
  margin-top: 30px;
  padding: 25px;
  border-radius: 12px;
  border: 1px solid #ddd;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.role-label {
  color: #800000;
  margin-bottom: 15px;
  font-weight: bold;
  text-transform: uppercase;
  font-size: 1rem;
}

.pup-btn {
  background-color: #800000;
  color: white;
  border: none;
  padding: 10px 20px;
  margin-right: 10px;
  cursor: pointer;
  font-weight: bold;
  border-radius: 4px;
  transition: opacity 0.2s;
}

.pup-btn:hover {
  opacity: 0.9;
}

.loading-state, .error-state {
  text-align: center;
  padding: 40px;
  color: #666;
}

/* Background colors para sa iba't ibang roles */
.admin-bg { background-color: #fff9f9; }
.faculty-bg { background-color: #f9fbff; }
.student-bg { background-color: #f9fff9; }
</style>