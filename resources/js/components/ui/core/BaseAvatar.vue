<template>
  <div class="base-avatar" :style="{ width: size, height: size }">
    <img 
      v-if="src" 
      :src="src" 
      :alt="`Profile of ${initials}`"
      @error="handleImageError"
    />
    <span v-else class="initials-fallback">
      {{ initials }}
    </span>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  src: {
    type: String,
    default: null
  },
  initials: {
    type: String,
    default: 'US' // Default for a generic user [cite: 8, 37, 46]
  },
  size: {
    type: String,
    default: '48px'
  }
});

// Simple logic to hide the img if it fails to load
const handleImageError = (event) => {
  event.target.style.display = 'none';
};
</script>

<style scoped>
.base-avatar {
  border-radius: 50%; /* Force perfect circle [cite: 312] */
  overflow: hidden;
  background-color: #e0e0e0;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.base-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover; /* Ensures image covers the circle without stretching [cite: 312] */
}

.initials-fallback {
  color: #555;
  font-weight: bold;
  /* Use calc() to make initials scale appropriately */
  font-size: calc(v-bind(size) * 0.4); 
}
</style>