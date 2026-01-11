<template>
  <div class="card-wrapper" @click="$emit('click')">
    <div 
      class="card-body" 
      :style="{ backgroundColor: color }"
    ></div>
    
    <div class="card-label">
      {{ title }}
    </div>
  </div>
</template>

<script setup>
/**
 * PROPS
 * title: The text displayed below the card
 * color: The background color of the card body (default is white)
 */
defineProps({
  title: {
    type: String,
    required: true
  },
  color: {
    type: String,
    default: '#ffffff'
  }
});

defineEmits(['click']);
</script>

<style scoped>
.card-wrapper {
  width: 300px;
  display: flex;
  flex-direction: column;
  align-items: center;
  cursor: pointer;
  transition: transform 0.2s;
}

.card-wrapper:hover {
  transform: translateY(-5px);
}

.card-body {
  width: 100%;
  height: 180px;
  border-radius: 20px;
  border: 1px solid #e0e0e0;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  transition: background-color 0.3s ease;
}

.card-label {
  margin-top: 15px;
  font-family: 'Courier New', Courier, monospace;
  font-weight: bold;
  font-size: 1.5rem;
  text-align: center;
  color: #000;
  letter-spacing: 0.5px;
  width: 100%; /* Ensure it respects the wrapper width */
  
  /* Hard-cut truncation logic */
  white-space: nowrap; 
  overflow: hidden;    /* Changed from visible to hidden to enable cutting */
  text-overflow: clip; 
}
</style>