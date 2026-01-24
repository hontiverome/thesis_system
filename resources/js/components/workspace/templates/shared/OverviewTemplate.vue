<template>
  <div class="overview-template">
    <BaseCard :title="`${courseTitle} Overview` ">
      <p>Welcome to the dashboard for {{ courseTitle }}.</p>
      <p>You are viewing this as a {{ roleLabel }}.</p>
    </BaseCard>
  </div>
</template>

<script setup>
import BaseCard from '@/components/ui/core/BaseCard.vue';
// Props passed from TabHandler.vue
defineProps({
  role: String,
  roleLabel: String,
  courseTitle: String,
  tab: String
});
</script>