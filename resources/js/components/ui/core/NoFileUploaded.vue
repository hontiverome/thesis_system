<template>
  <div class="empty-state-container">
    <div class="content-box">
      <div class="icon-container">
        <svg 
          viewBox="0 0 24 24" 
          width="48" 
          height="48" 
          fill="none" 
          stroke="currentColor" 
          stroke-width="1.5" 
          stroke-linecap="round" 
          stroke-linejoin="round"
        >
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
          <path d="M12 18v-6m-3 3 3-3 3 3"></path>
        </svg>
      </div>

      <h2 class="title">Your Group have not yet uploaded the File.</h2>
      <p class="subtitle">PDF format Only.</p>
    </div>
  </div>
</template>

<style scoped>
.empty-state-container {
  display: flex;
  align-items: center;
  justify-content: center;
  /* Adjust height as needed for your layout */
  min-height: 300px; 
  padding: 2rem;
  background-color: #ffffff;
}

.content-box {
  text-align: center;
  max-width: 600px;
}

.icon-container {
  color: #1a1a1b; /* Dark slate color matching your icon */
  margin-bottom: 1.5rem;
  display: flex;
  justify-content: center;
}

.title {
  font-family: sans-serif;
  font-size: 1.75rem;
  font-weight: 600;
  color: #000000;
  margin: 0 0 0.5rem 0;
  letter-spacing: -0.01em;
}

.subtitle {
  font-family: sans-serif;
  font-size: 1.25rem;
  color: #757575; /* Muted gray for the format text */
  margin: 0;
}
</style>