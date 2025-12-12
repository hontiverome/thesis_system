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
  /* 💡 MODIFIED: Increased width to 400px to accommodate full titles */
  width: 400px; 
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
  border: 1px solid #F4F6F9; 
  box-shadow: 0 4px 6px #F4F6F9; 
}

.card-label {
  margin-top: 15px;
  font-family: 'Courier New', Courier, monospace;
  font-weight: bold;
  font-size: 1.5rem;
  text-align: center;
  color: #000;
  letter-spacing: 0.5px;
  max-width: 90%;
  
  /* Retained to enforce single line */
  white-space: nowrap; 
  /* Retained to hide any overflow if a title is EXTREMELY long */
  overflow: hidden; 
  
  /* text-overflow: ellipsis is intentionally omitted */
}
</style>