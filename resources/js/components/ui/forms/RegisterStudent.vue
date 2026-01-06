<template>
  <div class="panel red-panel">
    <div class="panel-content">
      
      <header class="pup-header">
        <h1>POLYTECHNIC UNIVERSITY<br>OF THE PHILIPPINES</h1>

        <img src="/pup-logo.png" alt="PUP Logo" class="pup-logo"/>

      </header>
      
      <div class="welcome-section">
        <h2 class="welcome-text">WELCOME BACK!</h2>
        <p class="cta-text">DON'T HAVE AN ACCOUNT?</p>
        
        <button class="register-button">REGISTER</button>
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
  justify-content: center; 
  /* FIX: Vertically centers all children (h1 and img) */
  align-items: center; 
  gap: 15px; 
  width: 100%;
  margin-bottom: 50px; 
}

.pup-header h1 {
  /* School Name Text */
  font-family: Source Sans Pro, sans-serif; 
  font-size: 1.3em;
  font-weight: 700; /* Bold */
  /* Keeps the two lines of text centered relative to each other */
  text-align: center;
  line-height: 1.3;
  margin: 0;
  white-space: normal;
}

.pup-logo {
  width: 120px; 
  height: 120px; 
  background: white; 
  border-radius: 50%; 
  flex-shrink: 0; 
  /* Ensures no top margin is fighting the vertical alignment */
  margin-top: 0; 
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
  margin-bottom: 40px;
}

.register-button {
  /* REGISTER Button Text */
  font-family: 'Roboto', sans-serif; 
  padding: 15px 40px;
  font-size: 1.2em;
  font-weight: 700; /* Bold */
  letter-spacing: 0.1em;
  color: #800000;
  background-color: white;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  transition: background-color 0.3s;
}

.register-button:hover {
  background-color: #eee;
}
</style>