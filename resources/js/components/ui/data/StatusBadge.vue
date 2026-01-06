<template>
  <div 
    class="flex items-center space-x-1 px-3 py-1 rounded-full text-xs font-medium"
    :class="[statusClasses]"
  >
    <BaseIcon :name="iconName" size="16px" />
    <span>{{ statusText }}</span>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import BaseIcon from '../core/BaseIcon.vue';

const props = defineProps({
  status: {
    type: String,
    required: true,
    validator: (val) => ['submitted', 'approved', 'declined', 'pending'].includes(val)
  }
});

const statusClasses = computed(() => {
  switch (props.status) {
    case 'approved':
      return 'bg-green-100 text-green-800'; // Green for success
    case 'declined':
      return 'bg-red-100 text-red-800'; // Red for rejection
    case 'submitted':
      return 'bg-blue-100 text-blue-800'; // Blue for general submission [cite: 64]
    case 'pending':
    default:
      return 'bg-yellow-100 text-yellow-800'; // Yellow for awaiting action
  }
});

const iconName = computed(() => {
  switch (props.status) {
    case 'approved': return 'check-circle';
    case 'declined': return 'close-circle';
    case 'submitted': return 'clock';
    default: return 'star'; // Star for Panel Status
  }
});

const statusText = computed(() => {
  return props.status.charAt(0).toUpperCase() + props.status.slice(1);
});
</script>