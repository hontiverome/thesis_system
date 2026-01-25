// ChaptersTemplate.vue is a shared template used for displaying chapters 1-3 related information 
// and actions in various views for students.
<template>
  <div class="proposals-wrapper">
    <div v-if="isFeatureReady">
       </div>

    <ComingSoonTemplate v-else />
  </div>
</template>

<script setup>
import ComingSoonTemplate from '@/views/workspace/shared/ComingSoonTemplate.vue'; //

const isFeatureReady = false; 
</script>