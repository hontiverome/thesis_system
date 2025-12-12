<template>
  <button
    :class="[baseClasses, variantClasses, sizeClasses]"
    :type="type"
  >
    <slot></slot>
  </button>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  // 'dark-red' (like Reject [cite: 122, 135]), 'green' (like Accept [cite: 121, 134]), 'grey'
  variant: {
    type: String,
    default: 'grey', 
    validator: (val) => ['dark-red', 'green', 'grey'].includes(val)
  },
  // 'large' (for main actions), 'small' (for tiny actions like Accept/Reject)
  size: {
    type: String,
    default: 'medium',
    validator: (val) => ['small', 'medium', 'large'].includes(val)
  },
  type: {
    type: String,
    default: 'button'
  }
});

const baseClasses = 'font-semibold rounded transition duration-150';

const variantClasses = computed(() => {
  switch (props.variant) {
    case 'dark-red':
      return 'bg-red-700 text-white hover:bg-red-800'; // Represents Reject action [cite: 122]
    case 'green':
      return 'bg-green-600 text-white hover:bg-green-700'; // Represents Accept action [cite: 121]
    case 'grey':
      return 'bg-gray-200 text-gray-800 hover:bg-gray-300';
    default:
      return 'bg-blue-600 text-white hover:bg-blue-700';
  }
});

const sizeClasses = computed(() => {
  switch (props.size) {
    case 'small':
      return 'px-2 py-1 text-xs'; // Tiny Accept/Reject buttons [cite: 121, 122]
    case 'large':
      return 'px-6 py-3 text-lg w-full'; // For big buttons like Login
    default:
      return 'px-4 py-2 text-sm';
  }
});
</script>