<template>
  <div class="panel red-panel">
    <div class="panel-content">
      
      <header class="pup-header">
        <h1>POLYTECHNIC UNIVERSITY<br>OF THE PHILIPPINES</h1>
        
        <img src="/pup-logo.png" alt="PUP Logo" class="pup-logo"/>
      </header>
      
      <div class="welcome-section">
        <h2 class="welcome-text">WELCOME BACK!</h2>
        
        <p class="cta-text">
          DON'T HAVE<br>AN ACCOUNT?
        </p>
        
        </div>
      
    </div>
  </div>
</template>


<script>
export default {
  name: 'WelcomePanel',
};
</script>


<style scoped>


.panel-content {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
}


.pup-header {
  display: flex;
  /* Retaining align-items: flex-start from your input, 
     which aligns the top of the text and logo */
  justify-content: center;
  align-items: flex-start;
  gap: 15px;
  width: 100%;
  margin-bottom: 50px;
}


.pup-header h1 {
  /* School Name Text */
  font-family: Source Sans Pro, sans-serif;
  font-size: 1.1em;
  font-weight: 700; /* Bold */
  text-align: center;
  line-height: 1.3;
  margin: 0;
  white-space: normal;
}


.pup-logo {
  width: 50px;
  height: 50px;
  background: white;
  border-radius: 50%;
  flex-shrink: 0;
  margin-top: 5px;
}


.welcome-section {
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
}


.welcome-text {
  /* WELCOME BACK! Text */
  font-family: 'Sue Ellen Francisco', cursive, sans-serif;
  font-size: 3em;
  margin-bottom: 5px;
  white-space: nowrap;
}


.cta-text {
  /* DON'T HAVE AN ACCOUNT? Text */
  font-family: 'Source Sans Pro', sans-serif;
  font-size: 0.9em;
  font-weight: 500; /* Medium weight */
  opacity: 0.8;
  letter-spacing: 0.1em;
  /* Margin-bottom remains, even without the button */
  margin-bottom: 40px; 
}

</style>