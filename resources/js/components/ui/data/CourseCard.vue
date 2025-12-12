<template>
  <div class="card-wrapper">
    <div class="card-body"></div>
    <div class="card-label">{{ title }}</div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true,
    default: 'Methods of Research' 
  }
});
</script>

<style scoped>
.card-wrapper {
  /* Added for consistent sizing */
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
  background-color: #ffffff; 
  border-radius: 20px;
  border: 1px solid #e0e0e0; 
  box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
}

.card-label {
  margin-top: 15px;
  font-family: 'Courier New', Courier, monospace;
  font-weight: bold;
  font-size: 1.5rem;
  text-align: center;
  color: #000;
  letter-spacing: 0.5px;
  max-width: 100%;
  
  /* --- The THREE properties required for single-line, hard-cut truncation --- */
  white-space: nowrap;  /* 1. Prevents the text from wrapping */
  overflow: visible;     /* 2. Hides any text that extends past the boundary */
  text-overflow: clip;  /* 3. Explicitly ensures no ellipsis is shown */
}
</style>