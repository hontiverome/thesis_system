<template>
  <div class="background-wrapper">
    <div class="content-overlay">
      
      <div class="panel red-panel">
        <div class="panel-content">
          
          <div class="nav-header">
            <BaseIcon 
              name="mdi:chevron-left" 
              size="32px" 
              color="#fff" 
              class="back-arrow" 
              @click="$emit('go-back')" 
            />
          </div>

          <h2></h2>
          <p></p>
          <div class="form-placeholder">
          </div>
          
        </div>
      </div>

      <div class="panel white-panel">
        <div class="panel-content">
          <h2></h2>
          <ul>
            <li></li>
            <li></li>
            <li></li>
          </ul>
        </div>
      </div>
      
    </div>
  </div>
</template>

<script>
// 1. Import the BaseIcon component
import BaseIcon from '@/components/ui/core/BaseIcon.vue'; // Adjust path if necessary

export default {
  name: 'RegisterLayout',
  components: {
    BaseIcon, // 2. Register the component
  }
  // No need for setup or defines unless you are using the @click event
};
</script>

<style scoped>
/* -------------------------------------------------------------
  *** GLOBAL & SCROLLBAR FIXES (UNCHANGED) ***
  ------------------------------------------------------------- */
html, body {
  margin: 0;
  padding: 0;
  height: 100%;
  overflow: hidden;
}

body {
  font-family: Arial, sans-serif;
}

/* ---------------------------------
  *** BACKGROUND WRAPPER STYLES ***
  ---------------------------------
*/
.background-wrapper {
  height: 100%;
  width: 100%;
  
  background-image: url('/Register_BG.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  
  display: flex;
  justify-content: center;
  box-sizing: border-box;
}

/* ---------------------------------
  *** CONTENT OVERLAY STYLES (UNCHANGED) ***
  ---------------------------------
*/
.content-overlay {
  height: 100%;
  width: 100%;
  display: flex;
  gap: 40px;
  overflow: hidden;
  box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
}

/* ---------------------------------
  *** PANEL STYLES (MODIFIED) ***
  ---------------------------------
*/
.panel {
  flex: 1;
  /* CRITICAL: Set position relative so the absolute icon is contained */
  position: relative; 
  padding: 40px;
  
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
}

.panel-content {
  width: 100%;
}

/* ---------------------------------
  *** ICON STYLING ***
  ---------------------------------
*/

.back-arrow {
  cursor: pointer;
  /* Position absolute relative to the .panel, compensating for its 40px padding */
  position: absolute; 
  top: 15px; /* Adjust this value for vertical position */
  left: 15px; /* Adjust this value for horizontal position */
  z-index: 10;
  transition: opacity 0.2s;
}

.back-arrow:hover {
    opacity: 0.8;
}

/* Styles for the Dark Red Panel (Left) - UNCHANGED */
.red-panel {
  background-color: rgba(139, 0, 0, 0.9);
  color: white;
  border-top-right-radius: 25px;
  border-bottom-right-radius: 25px;
}

/* Styles for the White/Translucent Panel (Right) - UNCHANGED */
.white-panel {
  background-color: rgba(255, 255, 255, 0.45);
  color: #333;
  border-top-left-radius: 25px;
  border-bottom-left-radius: 25px;
}

/* Typography and List Styles (UNCHANGED) */
.panel h2 {
  font-size: 2.2em;
  margin-top: 0;
  margin-bottom: 10px;
}

.white-panel ul {
  list-style: none;
  padding: 0;
  text-align: left;
  margin-bottom: 25px;
}

.white-panel li {
  padding: 8px 0;
  min-height: 1.2em; 
}


/* ---------------------------------
  *** RESPONSIVENESS (UNCHANGED) ***
  ---------------------------------
*/
@media (max-width: 900px) {
  .content-overlay {
    flex-direction: column;
    gap: 20px;
    padding: 0;
    width: 95%;
    height: auto;
  }
  
  .red-panel {
    border-radius: 25px 25px 0 0;
  }
  
  .white-panel {
    border-radius: 0 0 25px 25px;
  }
}
</style>