<template>
  <div class="flex items-center space-x-1 text-sm font-medium text-gray-600">
    <BaseIcon name="account-group" size="20px" />
    <span>{{ count }}/{{ max }}</span>
  </div>
</template>

<script setup>
import BaseIcon from '../core/BaseIcon.vue';

defineProps({
  count: {
    type: Number,
    required: true // The current count, e.g., 4 [cite: 120]
  },
  max: {
    type: Number,
    required: true // The maximum count, e.g., 9 [cite: 120]
  }
});
</script>