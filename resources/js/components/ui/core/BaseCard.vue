<template>
  <div class="base-card">
    <header class="card-header">
      <span class="title-left">{{ title }}</span>

      <div class="title-right">
        <slot name="header-right"></slot>
      </div>
    </header>

    <div class="card-content">
      <slot></slot>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true
  }
});
</script>

<style scoped>
.base-card, 
.course-content-container {
  max-width: 100% !important;
  width: 100%;
  margin-left: 0;
  margin-right: 0;
}

.card-header {
  display: flex; /* Makes the left and right items sit on one line */
  justify-content: space-between; /* Pushes one to left, one to right */
  align-items: baseline; /* Keeps text aligned at the bottom */
  padding: 40px 40px 20px 40px;
}

.title-left {
  color: #800000;
  font-family: Arial, sans-serif;
  font-size: 1.9em;
  font-weight: bold;
  letter-spacing: 2px;
  text-transform: uppercase;
}

.title-right {
  color: #666;
  font-family: Arial, sans-serif;
  font-size: 1.2em;
}

.card-content {
  padding: 0 40px 20px 40px;
}
</style>