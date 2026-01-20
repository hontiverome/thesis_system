<template>
  <SubmissionContainer>
    <template #extra-feature>
      <div class="evaluation-card">
        <h4>Panel Evaluation</h4>
        <div class="score">Pending Defense</div>
      </div>
    </template>
  </SubmissionContainer>
</template>

<script setup>
import SubmissionContainer from '@/components/course/SubmissionContainer.vue';
</script>

<style scoped>
.evaluation-card { background: #f9f9f9; padding: 20px; border-radius: 8px; border-left: 5px solid #800000; }
</style>