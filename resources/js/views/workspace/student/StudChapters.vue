// Student Chapter Component for Chapters 1-3 View

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