<template>
  <div class="courses page-view" role="main">
    <BaseCard title="COURSES">
      <BaseCourseOverview :subTitle="displaySubTitle">
        
        <div class="courses-grid">
          <div 
            v-for="course in roleCourses" 
            :key="course.id"
            class="course-card-wrapper"
            @click="navigateToCourse(course.path)"
          >
            <CourseCard 
              :title="course.title"
              class="clickable-card"
            />
          </div>
        </div>

        <div class="interface-container">
          <!-- <div :class="['role-workspace', `${role}-bg`]">
            <h3 class="role-label">{{ roleLabel }} Workspace</h3>
            <div class="action-row">
              <button v-for="action in roleActions" :key="action" class="pup-btn">
                {{ action }}
              </button>
            </div>
          </div> -->
        </div>

      </BaseCourseOverview>
    </BaseCard>

    <router-view />
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import BaseCard from '@/components/ui/core/BaseCard.vue';
import BaseCourseOverview from '@/components/ui/core/BaseCourseOverview.vue';
import CourseCard from '@/components/ui/data/CourseCard.vue';

const route = useRoute();
const router = useRouter();

// Extract role from URL path (e.g., /student/courses -> student)
const role = computed(() => route.path.split('/')[1]);

const displaySubTitle = computed(() => {
  const subtitles = {
    student: 'COURSES OVERVIEW FOR STUDENT',
    admin: 'COURSES OVERVIEW FOR ADMIN',
    adviser: 'COURSES OVERVIEW FOR ADVISER',
    faculty: 'COURSES OVERVIEW FOR FACULTY'
  };
  return subtitles[role.value] || 'COURSE OVERVIEW';
});

const roleLabel = computed(() => {
  const labels = {
    student: 'Student',
    admin: 'Administrator',
    adviser: 'Adviser',
    faculty: 'Faculty'
  };
  return labels[role.value] || 'User';
});

const roleActions = computed(() => {
  const actions = {
    student: ['View My Progress', 'Track Submissions'],
    admin: ['Manage Curriculum', 'View System Logs'],
    adviser: ['My Advising Load', 'Grade Submissions'],
    faculty: ['My Classes', 'Grade Submissions']
  };
  return actions[role.value] || [];
});

// Courses mapped to their paths in the URL
const roleCourses = [
  { id: 1, title: 'Methods of Research', path: 'mor' },
  { id: 2, title: 'Project Design 1', path: 'dp1' },
  { id: 3, title: 'Project Design 2', path: 'dp2' }
];

const navigateToCourse = (coursePath) => {
  router.push({
    name: `${role.value}-workspace-tab`, 
    params: { 
      role: role.value,
      course: coursePath,
      tab: 'overview'
    }
  });
};
</script>

<style scoped>
.page-view {
  padding: 50px;
  background-color: #f4f6f9;
  min-height: 100vh;
  width: 100%;
}

.courses-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 40px;
  justify-items: center;
  padding: 20px 0;
}

.course-card-wrapper {
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
}

.course-card-wrapper:hover {
  transform: translateY(-5px);
}

.clickable-card {
  height: 100%;
}

.interface-container {
  margin-top: 30px;
}

.role-workspace {
  padding: 25px;
  border-radius: 12px;
  border: 1px solid #ddd;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.role-label {
  color: #800000;
  margin-bottom: 15px;
  font-weight: bold;
  text-transform: uppercase;
  font-size: 1rem;
}

.action-row {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

.pup-btn {
  background-color: #800000;
  color: white;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  font-weight: bold;
  border-radius: 4px;
  transition: opacity 0.2s;
}

.pup-btn:hover {
  opacity: 0.9;
}

/* Background colors for different roles */
.student-bg {
  background-color: #f9fff9;
}

.admin-bg {
  background-color: #fff9f9;
}

.adviser-bg {
  background-color: #f9fbff;
}

.faculty-bg {
  background-color: #f9fbff;
}
</style>
