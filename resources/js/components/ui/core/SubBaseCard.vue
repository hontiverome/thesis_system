<template>
  <div class="rounded-card">
    <h2 class="title-header">TITLE PROPOSALS</h2>
    
    <slot></slot>
  </div>
</template>

<script>
export default {
  // No name property, just the component
}
</script>

<style scoped>
/* Container Style */
.rounded-card {
  background-color: #FFFFFF;
  border-radius: 20px;
  padding: 40px;
  max-width: 800px;
  margin: 20px auto;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  font-family: 'Arial', sans-serif; /* Clean font stack */
}

/* Title Text Style */
.title-header {
  font-size: 1.5rem;       /* Matches the size in the image */
  font-weight: 900;        /* Extra bold/Black weight */
  color: #000000;          /* Solid Black */
  text-transform: uppercase;
  letter-spacing: 3px;     /* Wide spacing between letters */
  margin: 0;               /* Resets default browser margins */
  margin-bottom: 20px;     /* Optional space below the title */

}
</style>