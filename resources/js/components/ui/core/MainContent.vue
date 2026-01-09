<template>
  <main class="main-viewport">
    <div class="content-container">
      <div class="info-grid">
        <div class="info-group">
          <h2 class="label-text">GROUP #</h2>
          <h2 class="label-text">SECTION</h2>
        </div>

        <div class="info-group text-right">
          <h2 class="label-text">ASSIGNED ADVISER</h2>
        </div>
      </div>

      <div class="content-body">
        </div>
    </div>
  </main>
</template>

<style scoped>
/* IMPORTANT: To fully remove scrolling, ensure your global styles 
   (index.html or App.vue) have 'body { margin: 0; overflow: hidden; }'
*/

.main-viewport {
  /* Offset for fixed header and sidebar */
  margin-top: 80px;
  margin-left: 280px;
  
  /* Lock height to exactly the remaining screen space */
  height: calc(100vh - 80px);
  
  /* Disable scrolling */
  overflow: hidden; 
  
  background-color: #F4F6F9; /* Light gray background */
  padding: 40px;
  box-sizing: border-box;
  
  /* Optional: Remove visual scrollbars in case content overflows slightly */
  scrollbar-width: none; /* Firefox */
  -ms-overflow-style: none; /* IE/Edge */
}

/* Chrome/Safari: Hide scrollbar */
.main-viewport::-webkit-scrollbar {
  display: none;
}

.content-container {
  max-width: 1200px;
  margin: 0 auto;
  height: 100%; /* Ensures container fills the viewport */
}

.info-grid {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.info-group {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.label-text {
  font-family: sans-serif;
  color: #800000; /* PUP Red */
  font-size: 1.5rem;
  font-weight: 700;
  margin: 0;
  letter-spacing: 1px;
}

.text-right {
  text-align: right;
}

.content-body {
  margin-top: 40px;
}

/* Responsiveness: Stack content on smaller screens */
@media (max-width: 1024px) {
  .main-viewport {
    margin-left: 0; 
    padding: 20px;
    height: auto; /* Allow scrolling on mobile if items stack */
    overflow: visible;
  }
  
  .info-grid {
    flex-direction: column;
    gap: 20px;
  }
  
  .text-right {
    text-align: left;
  }
}
</style>