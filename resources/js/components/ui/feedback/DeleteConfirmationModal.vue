<template>
  <div class="modal-overlay" @click.self="$emit('cancel')">
    <div class="modal-card">
      
      <div class="icon-circle">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="trash-icon">
          <polyline points="3 6 5 6 21 6"></polyline>
          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
        </svg>
      </div>

      <h2 class="modal-title">Delete File</h2>
      <p class="modal-message">
        Are you sure you want to <br> delete?
      </p>

      <div class="button-group">
        
        <button class="btn btn-delete" @click="$emit('confirm')">
          Delete
        </button>

        <button class="btn btn-cancel" @click="$emit('cancel')">
          Cancel
        </button>

      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: 'DeleteConfirmationModal'
  // No props needed, just listens for events
}
</script>

<style scoped>
/* --- 1. Overlay (Darkens the background) --- */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.3); /* Dimmed background */
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000; /* Ensures it sits on top of everything */
}

/* --- 2. The White Card --- */
.modal-card {
  background-color: #FFFFFF;
  border-radius: 20px;
  padding: 30px;
  width: 90%;
  max-width: 400px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  position: relative;
  text-align: center;
  font-family: 'Courier New', Courier, monospace; /* Matches the typewriter font in image */
}

/* --- 3. Icon Styling --- */
.icon-circle {
  position: absolute;
  top: 20px;
  left: 20px;
  width: 50px;
  height: 50px;
  background-color: #FFCDD2; /* Light Red/Pink circle */
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.trash-icon {
  width: 24px;
  height: 24px;
  stroke: #800000; /* Dark Maroon Trash Icon */
}

/* --- 4. Text Styling --- */
.modal-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #000;
  margin-top: 60px; /* Space for the icon above */
  margin-bottom: 15px;
  text-align: left; /* Aligns title slightly left like in image */
  padding-left: 10px;
}

.modal-message {
  font-size: 1.1rem;
  color: #000;
  margin-bottom: 30px;
  line-height: 1.4;
}

/* --- 5. Buttons --- */
.button-group {
  display: flex;
  justify-content: center;
  gap: 20px;
}

.btn {
  padding: 10px 30px;
  border: none;
  border-radius: 8px;
  font-family: 'Arial', sans-serif; /* Buttons often look better in sans-serif */
  font-size: 1rem;
  cursor: pointer;
  font-weight: 500;
  transition: opacity 0.2s;
}

.btn:hover {
  opacity: 0.9;
}

.btn-delete {
  background-color: #800000; /* Deep Red */
  color: #FFFFFF;
}

.btn-cancel {
  background-color: #c7c5c5; /* Grey */
  color: #000000;
}
</style>