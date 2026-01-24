<template>
  <div class="sections-page">
    <BaseCard title="CLASS SECTIONS">
      <BaseCourseOverview :subTitle="`${courseTitle} - CLASS SECTIONS`">
        <div class="sections-header">
          <div class="header-main">
            <h1 class="main-heading">CLASS SECTIONS</h1>
            <button v-if="role === 'admin'" class="add-section-btn" @click="openAddSectionModal">
              + Add Section
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
      </BaseCourseOverview>

      <div class="action-buttons">
        <button class="back-btn" @click="goBack">← Back to Courses</button>
      </div>
    </BaseCard>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import BaseCard from '@/components/ui/core/BaseCard.vue';
import BaseCourseOverview from '@/components/ui/core/BaseCourseOverview.vue';
import CourseCard from '@/components/ui/data/CourseCard.vue';

const route = useRoute();
const router = useRouter();

// Get role and course from URL
const role = computed(() => route.params.role);
const courseName = computed(() => route.params.course);

// Map course codes to titles
const courseMap = {
  mor: 'Methods of Research',
  dp1: 'Project Design 1',
  dp2: 'Project Design 2'
};

const courseTitle = computed(() => courseMap[courseName.value] || 'Course');

// Use a ref for sections so we can add to it
const sections = ref([
  { id: 1, name: '3-1', course: courseName.value },
  { id: 2, name: '3-2', course: courseName.value },
  { id: 3, name: '3-3', course: courseName.value },
  { id: 4, name: '3-4', course: courseName.value },
  { id: 5, name: '3-5', course: courseName.value },
  { id: 6, name: '3-6', course: courseName.value },
]);

const openAddSectionModal = () => {
  // Logic to show a modal or prompt for a new section name
  const newSectionName = prompt("Enter New Section Name (e.g., 3-7):");
  if (newSectionName) {
    const newId = sections.value.length + 1;
    sections.value.push({
      id: newId,
      name: newSectionName,
      course: courseName.value
    });
  }
};

const navigateToSection = (section) => {
  router.push({
    // Dynamically calls 'admin-section-detail' or 'faculty-section-detail'
    name: `${role.value}-section-detail`, 
    params: { 
      role: role.value, 
      course: courseName.value,
      section: section.name 
    }
  });
};

const goBack = () => {
  router.back();
};
</script>

<style scoped>
.sections-page {
  padding: 50px;
  background-color: #f4f6f9;
  min-height: 100vh;
  width: 100%;
}

.sections-header {
  margin-bottom: 30px;
  text-align: left;
}

.header-main {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.main-heading {
  color: #800000;
  font-size: 2rem;
  margin: 0 0 10px 0;
  text-transform: uppercase;
  font-weight: bold;
}

.add-section-btn {
  background-color: #28a745;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.2s;
}

.add-section-btn:hover {
  background-color: #218838;
}

.sub-heading {
  color: #666;
  font-size: 1rem;
  margin: 0;
  font-style: italic;
  text-transform: uppercase;
}

.sections-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 40px;
  justify-items: center;
  padding: 20px 0;
  margin: 30px 0;
}

.section-card-wrapper {
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
}

.section-card-wrapper:hover {
  transform: translateY(-5px);
}

.action-buttons {
  margin-top: 40px;
  display: flex;
  gap: 10px;
}

.back-btn {
  background-color: #800000;
  color: white;
  border: none;
  padding: 12px 24px;
  cursor: pointer;
  font-weight: bold;
  border-radius: 4px;
  transition: opacity 0.2s;
}

.back-btn:hover {
  opacity: 0.9;
}

@media (max-width: 768px) {
  .sections-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
  }
}
</style>