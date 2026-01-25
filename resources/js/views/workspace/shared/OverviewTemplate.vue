// OverviewTemplate is a shared template used for displaying an overview dashboard
// in various views for faculty, admin, advisers, and students.

<template>
  <div class="overview-template">
    <BaseCard :title="` ${courseTitleComputed} Overview`">
      <p>Welcome to the dashboard (coming soon) for {{ courseTitleComputed }}.</p>
      <p>You are viewing this as a {{ roleLabelComputed }}.</p>
  <div class="inline-flex items-center bg-maroon-50 border border-maroon-200 rounded-xl p-12 shadow-sm">
    <div class="text-left">
      <p class="text-sm text-maroon-800 leading-relaxed">
        In the meantime, you can access all features and tasks <br />
        by navigating through the <strong>Sidebar Menu</strong> on the left.
      </p>
    </div>
  </div>
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

.bg-maroon-50 {
  background-color: #fffafb;
}
.text-maroon-900 {
  color: #500000;
}
.text-maroon-800 {
  color: #800000;
}
.border-maroon-200 {
  border-color: #f5e6e6;
  padding: 0.5%;
}

</style>