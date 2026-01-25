// OverviewTemplate is a shared template used for displaying an overview dashboard
// in various views for faculty, admin, advisers, and students.

<template>
  <div class="overview-template">
    <BaseCard :title="`${courseTitleComputed} Overview`">
      <p>Welcome to the dashboard for {{ courseTitleComputed }}.</p>
      <p>You are viewing this as a {{ roleLabelComputed }}.</p>
    </BaseCard>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import BaseCard from '@/components/ui/core/BaseCard.vue';

const props = defineProps({
  role: String,
  roleLabel: String,
  courseTitle: String,
  tab: String
});

const route = useRoute();

// Map course codes to full titles
const courseMap = {
  mor: 'Methods of Research',
  dp1: 'Project Design 1',
  dp2: 'Project Design 2'
};

const courseTitleComputed = computed(() => {
  return props.courseTitle || courseMap[route.params.course] || 'Course';
});

const roleLabelComputed = computed(() => {
  const roleMap = {
    admin: 'Administrator',
    faculty: 'Faculty',
    adviser: 'Adviser',
    student: 'Student'
  };
  return props.roleLabel || roleMap[route.params.role] || 'User';
});
</script>

<style scoped>
.overview-template {
  padding: 20px;
}

.overview-template p {
  font-size: 1rem;
  margin: 10px 0;
}
</style>