<template>
  <div class="white-container">
    <header v-if="title || $slots.right" class="container-header">
      <h2 class="container-title">{{ title }}</h2>
      
      <div class="container-right">
        <slot name="right"></slot>
      </div>
    </header>
    
    <div class="content-body">
      <slot></slot>
    </div>
  </div>
</template>

<script setup>
/**
 * PROPS
 * title: The main heading on the left.
 */
defineProps({
  title: {
    type: String,
    default: ''
  }
});
</script>

<style scoped>
.white-container {
  background-color: #ffffff;
  border-radius: 12px;
  box-shadow: 0 0 15px 5px rgba(0, 0, 0, 0.06);
  
  /* SIZE & SPACING */
  width: 95%;
  max-width: 1600px;
  margin: 40px auto;
  padding: 60px; /* Large internal padding for a "Big" look */
  box-sizing: border-box;
}

.container-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 40px;
  padding-bottom: 20px;
  border-bottom: 1px solid #f2f2f2; /* Subtle line to separate titles from content */
}

.container-title {
  color: #777777;
  font-family: Arial, sans-serif;
  font-size: 1.8rem;
  font-weight: bold;
  letter-spacing: 2px;
  text-transform: uppercase;
  margin: 0;
}

.container-right {
  color: #999999;
  font-family: Arial, sans-serif;
  font-size: 1.3rem;
  font-weight: 500;
}

.content-body {
  width: 100%;
  font-family: Arial, sans-serif;
  font-size: 1.1rem;
  line-height: 1.6;
  color: #333333;
}
</style>