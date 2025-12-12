<template>
  <IconifyIcon 
    :icon="name" 
    :style="{ 
      width: size, 
      height: size, 
      color: color 
    }" 
    class="base-icon"
  />
</template>

<script setup>
// *** CRITICAL FIX: Import the Iconify component ***
import { Icon as IconifyIcon } from '@iconify/vue'; 

const props = defineProps({
  name: {
    type: String, // e.g., 'mdi:magnify'
    required: true
  },
  size: {
    type: String,
    default: '24px' // Standard icon size
  },
  color: {
    type: String,
    default: 'currentColor'
  }
});
</script>

<style scoped>
.base-icon {
  display: inline-block;
  /* Ensures the icon container is a square of the specified size */
  line-height: 1;
}
</style>