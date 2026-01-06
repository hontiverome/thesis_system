<template>
  <div class="course-content-container">
    
    <div class="card-content">
      
      <div class="inner-header-container">
        <p class="course-heading-overview">COURSE OVERVIEW</p>
      </div>
      
      <slot></slot>
      
    </div>
    
  </div>
</template>

<script>
export default {
  name: 'CourseContentContainer' 
}
</script>

<style scoped>
/* ==========================================================
   1. STYLES FOR THE OUTER CONTAINER
   ========================================================== */

.course-content-container {
  /* Card Look: White background, rounded, shadowed */
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
  
  /* Size and Position */
  max-width: 1400px; 
  margin: 10px auto; 
  padding-bottom: 40px; 
}

/* Removed .card-header-main styles to clean up CSS */

.card-content {
    /* * ADJUSTMENT: We move the top padding here to replace the space 
     * left by the removed orange header. 
     * The original padding was 50px top. 
     */
    padding: 50px 50px 0 50px; /* Top, Right, Bottom, Left */
}


/* ==========================================================
   2. STYLES FOR THE INNER HEADER ("COURSE OVERVIEW")
   ========================================================== */

.inner-header-container {
    /* Adjusted padding-top to 0 because the main padding is on .card-content */
    padding-top: 0; 
    width: 100%;
    box-sizing: border-box;
    position: relative;
    z-index: 10;
    
    /* Maintains the visual separation from the slot content */
    padding-bottom: 20px; 
}

.course-heading-overview {
    color: #777777; 
    font-family: Arial, sans-serif;
    font-size: 1.2em; 
    font-weight: bold; 
    letter-spacing: 2px;
    text-transform: uppercase;
    
    margin: 0; 
    position: relative;
    z-index: 20; 
}
</style>