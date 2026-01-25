// PS. shared templates are used across multiple manager roles only (Admin, Faculty, Adviser).
// FacultyTemplate is a shared template used for displaying faculty-related information and action.

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