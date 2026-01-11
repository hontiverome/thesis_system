<template>
  <div class="page-wrapper">
    <div class="rounded-card">
      </div>
  </div>
</template>

<style scoped>
/* 1. Prevent the entire browser window from scrolling */
.page-wrapper {
  background-color: #ffffff;
  height: 100vh;
  width: 100vw;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0;
  padding: 0;
  /* This locks the viewport */
  overflow: hidden; 
  position: fixed; 
  top: 0;
  left: 0;
}

/* 2. The Card with Shadow Border */
.rounded-card {
  background-color: #ffffff; 
  border-radius: 16px;
  padding: 30px;
  
  /* Dimensions */
  width: 1100px;
  height: 750px;
  
  /* Responsive safety: ensures card doesn't exceed screen size */
  max-width: 95vw;
  max-height: 95vh;
  
  /* Box Logic */
  box-sizing: border-box;
  
  /* 3. Prevent the card itself from scrolling */
  overflow: hidden; 

  /* Shadow Border Effect */
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); 
  
  border: none;
}
</style>