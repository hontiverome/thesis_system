// resources/js/composables/useWorkspaceApi.js
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import { ROLE_METADATA } from '@/config/roleConfig';

export function useWorkspaceApi() {
  const route = useRoute();

  // 1. Extract context from URL
  const role = computed(() => route.params.role);
  const course = computed(() => route.params.course);
  const tab = computed(() => route.params.tab || route.params[0]);

  // 2. Fetch permissions from Config (No more hardcoded || logic!)
  const rolePermissions = computed(() => ROLE_METADATA[role.value]?.permissions || {
    canEdit: false,
    canEvaluate: false
  });

  const canEdit = computed(() => rolePermissions.value.canEdit);
  const canEvaluate = computed(() => rolePermissions.value.canEvaluate);

  // 3. Dynamic API endpoint generator
  const apiEndpoint = computed(() => `/api/${role.value}/${course.value}/${tab.value}`);

  return {
    role,
    course,
    tab,
    canEdit,
    canEvaluate,
    apiEndpoint,
    // get the pretty label (e.g., "Administrator") in UI
    roleLabel: computed(() => ROLE_METADATA[role.value]?.label || 'User')
  };
}