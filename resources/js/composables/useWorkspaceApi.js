// resources/js/composables/useWorkspaceApi.js
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import { ROLE_METADATA, COURSE_MAP } from '@/config/roleConfig';

export function useWorkspaceApi() {
  const route = useRoute();

  // Add a fallback to prevent 'undefined' errors during transitions
  const role = computed(() => route.params.role || 'student');
  const course = computed(() => route.params.course || '');
  const tab = computed(() => route.params.tab || 'overview');

  const roleConfig = computed(() => ROLE_METADATA[role.value] || ROLE_METADATA.student);

  return {
    role,
    course,
    tab,
    // Automatically builds: /api/student/mor/overview
    apiEndpoint: computed(() => `/api/${role.value}/${course.value}/${tab.value}`),
    roleLabel: computed(() => roleConfig.value.label || 'User'),
    canEdit: computed(() => roleConfig.value.permissions?.canEdit || false),
    canEvaluate: computed(() => roleConfig.value.permissions?.canEvaluate || false),
    courseTitle: computed(() => COURSE_MAP[course.value] || course.value?.toUpperCase())
  };
}