// Panel Template is used for displaying panel information and status across manager roles.

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