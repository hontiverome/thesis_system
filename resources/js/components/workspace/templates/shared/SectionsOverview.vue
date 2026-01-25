// SectionsOverview.vue - Overview of class sections for a specific course with role-based access.

<template>
  <div class="sections-page">
    <BaseCourseOverview :subTitle="courseTitleComputed">
      <div class="sections-header">
        <div class="header-main">
          <h1 class="main-heading">CLASS SECTIONS</h1>
          <button 
            v-if="roleComputed === 'admin'" 
            class="add-section-btn" 
            @click="openAddSectionModal"
          >
            Add Section
          </button>
        </div>
        <p class="sub-heading">FIRST YEAR</p>
      </div>

      <div class="sections-grid">
        <div 
          v-for="section in sections" 
          :key="section.id"
          class="section-card-wrapper"
          @click="navigateToSection(section)"
        >
          <CourseCard :title="section.name" />
        </div>
      </div>

      <div class="action-buttons">
        <button class="back-btn" @click="goBack">← Back to Courses</button>
      </div>
    </BaseCourseOverview>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import BaseCourseOverview from '@/components/ui/core/BaseCourseOverview.vue';
import CourseCard from '@/components/ui/data/CourseCard.vue';

// Props optionally passed from parent
const props = defineProps({
  role: String,
  course: String
});

const route = useRoute();
const router = useRouter();

// Computed role and course (props fallback to route params)
const roleComputed = computed(() => props.role || route.params.role || 'student');
const courseNameComputed = computed(() => props.course || route.params.course || 'mor');

const courseMap = {
  mor: 'Methods of Research',
  dp1: 'Project Design 1',
  dp2: 'Project Design 2'
};

const courseTitleComputed = computed(() => courseMap[courseNameComputed.value] || 'Course');

// Sections mock data
const sections = ref([
  { id: 1, name: '3-1', course: courseNameComputed.value },
  { id: 2, name: '3-2', course: courseNameComputed.value },
  { id: 3, name: '3-3', course: courseNameComputed.value },
  { id: 4, name: '3-4', course: courseNameComputed.value },
  { id: 5, name: '3-5', course: courseNameComputed.value },
  { id: 6, name: '3-6', course: courseNameComputed.value },
]);

const openAddSectionModal = () => {
  const newSectionName = prompt("Enter New Section Name (e.g., 3-7):");
  if (newSectionName) {
    const newId = sections.value.length + 1;
    sections.value.push({
      id: newId,
      name: newSectionName,
      course: courseNameComputed.value
    });
  }
};

const navigateToSection = (section) => {
  router.push({
    path: `/${roleComputed.value}/courses/${courseNameComputed.value}/sections/${section.name}`
  });
};

const goBack = () => {
  router.push({
    name: 'role-courses-overview',
    params: {
      role: roleComputed.value
    }
  });
};
</script>

<style scoped>
.sections-page {
  /* Using theme variables from app.css */
  background-color: var(--bg-color); 
  color: var(--text-color);
  min-height: 100vh;
  width: 100%;
}

.main-heading {
  /* Aligned with PUP university red or theme primary */
  color: #800000; 
  font-size: 2rem;
  margin: 0 0 10px 0;
  text-transform: uppercase;
  font-weight: bold;
}

.add-section-btn {
  /* Uses green from your chart variables in app.css */
  background-color: var(--chart-secondary, #28a745); 
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  font-weight: bold;
  cursor: pointer;
  transition: opacity 0.2s;
}

.add-section-btn:hover {
  opacity: 0.9;
}

.sub-heading {
  color: var(--text-color);
  opacity: 0.7;
  font-size: 1rem;
  font-style: italic;
  text-transform: uppercase;
}

.sections-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 40px;
  justify-items: center;
  padding: 20px 0;
}

.back-btn {
  background-color: #800000;
  color: white;
  border: none;
  padding: 12px 24px;
  cursor: pointer;
  font-weight: bold;
  border-radius: 4px;
}

@media (max-width: 768px) {
  .sections-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
  }
}
</style>