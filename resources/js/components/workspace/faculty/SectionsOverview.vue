<template>
  <div class="sections-page">
    <BaseCard :title="title">
      <BaseCourseOverview :subTitle="`${courseTitle} - ${subTitle}`">
        <div class="sections-header">
          <div class="header-main">
            <h1 class="main-heading">{{ subTitle }}</h1>
            <button 
              v-if="role === 'admin'" 
              class="add-section-btn" 
              @click="$emit('add-section')"
            >
              + Add Section
            </button>
          </div>
          <p class="sub-heading">{{ yearLevel }}</p>
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
        <button class="back-btn" @click="goBack">← Back</button>
      </div>
    </BaseCard>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import BaseCard from '@/components/ui/core/BaseCard.vue';
import BaseCourseOverview from '@/components/ui/core/BaseCourseOverview.vue';
import CourseCard from '@/components/ui/data/CourseCard.vue';

const props = defineProps({
  title: { type: String, default: 'CLASS SECTIONS' },
  subTitle: { type: String, default: 'SECTIONS' },
  yearLevel: { type: String, default: 'FIRST YEAR' },
  // Data passed from parent/API
  sections: { type: Array, required: true },
  courseTitle: { type: String, required: true }
});

const emit = defineEmits(['add-section']);
const route = useRoute();
const router = useRouter();

const role = computed(() => route.params.role);
const courseName = computed(() => route.params.course);

const navigateToSection = (section) => {
  router.push({
    name: 'section-detail', // Use a generic route name
    params: { 
      role: role.value, 
      course: courseName.value,
      section: section.name 
    }
  });
};

const goBack = () => router.back();
</script>

<style scoped>
.sections-page { padding: 50px; background-color: #f4f6f9; min-height: 100vh; width: 100%; }
.header-main { display: flex; justify-content: space-between; align-items: center; }
.main-heading { color: #800000; font-size: 2rem; margin: 0 0 10px 0; text-transform: uppercase; font-weight: bold; }
.add-section-btn { background-color: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; cursor: pointer; }
.sub-heading { color: #666; font-size: 1rem; margin: 0; font-style: italic; text-transform: uppercase; }
.sections-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px; margin: 30px 0; }
.section-card-wrapper { cursor: pointer; transition: transform 0.2s; }
.section-card-wrapper:hover { transform: translateY(-5px); }
.back-btn { background-color: #800000; color: white; border: none; padding: 12px 24px; cursor: pointer; border-radius: 4px; font-weight: bold; }
@media (max-width: 768px) { .sections-grid { grid-template-columns: repeat(2, 1fr); } }
</style>