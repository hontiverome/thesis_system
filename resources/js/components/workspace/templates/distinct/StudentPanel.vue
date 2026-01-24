// StudentPanel Template is used for the student's view of their panel information and status.

<template>
  <div class="tab-handler">
    <component 
      :is="activeTemplate" 
      v-bind="templateProps" 
    />
  </div>
</template>

<script setup>
import { computed, defineAsyncComponent } from 'vue';
import { useWorkspaceApi } from '@/composables/useWorkspaceApi';

const { role, tab, apiEndpoint, canEvaluate, courseTitle } = useWorkspaceApi();

const activeTemplate = computed(() => {
  const t = tab.value;
  const r = role.value;

  // 1. DISTINCT TEMPLATES (Role-Specific Logic)
  // Mapping based on your folder structure in image_fbd7f1.png
  if (t === 'evaluation') {
    return r === 'adviser' 
      ? defineAsyncComponent(() => import('@/components/workspace/templates/distinct/AdviserEvaluation.vue'))
      : defineAsyncComponent(() => import('@/components/workspace/templates/distinct/StudentEvaluation.vue'));
  }

  if (t === 'title-proposals' && r === 'student') {
    return defineAsyncComponent(() => import('@/components/workspace/templates/distinct/StudentProposals.vue'));
  }
  
  if (t === 'feedback') {
    return defineAsyncComponent(() => import('@/components/workspace/templates/distinct/StudentFeedback.vue'));
  }

  if (t === 'panel-status') {
    return defineAsyncComponent(() => import('@/components/workspace/templates/distinct/StudentPanel.vue'));
  }

  // 2. SHARED TEMPLATES (General Layouts)
  const submissionTabs = ['chapters1-3', 'revised-chapters1-3', 'research-thesis'];
  if (submissionTabs.includes(t)) {
    return defineAsyncComponent(() => import('@/components/workspace/templates/shared/SubmissionTemplate.vue'));
  }

  if (t === 'documents') {
    return defineAsyncComponent(() => import('@/components/workspace/templates/shared/DocumentTemplate.vue'));
  }

  // DEFAULT: Shared Overview
  // This fix addresses the "Failed to resolve import" error in image_06cc9b.png
  return defineAsyncComponent(() => import('@/components/workspace/templates/shared/OverviewTemplate.vue'));
});

const templateProps = computed(() => ({
  role: role.value,
  tab: tab.value,
  courseTitle: courseTitle.value,
  apiEndpoint: apiEndpoint.value,
  permissions: { canEvaluate: canEvaluate.value }
}));
</script>