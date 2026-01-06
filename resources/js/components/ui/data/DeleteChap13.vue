<template>
  <div class="file-card-wrapper">
    
    <h3 class="section-label">Uploaded File</h3>

    <div class="status-card" :class="{ 'has-file': !!fileName }">
      
      <div class="left-section">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="folder-icon">
          <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
        </svg>

        <div class="text-info">
          <span class="file-name">
            {{ fileName ? fileName : 'No File Uploaded' }}
          </span>

          <span 
            class="status-text" 
            :class="fileName ? 'status-ok' : 'status-missing'"
          >
            {{ fileName ? 'Ready to Submit' : 'Missing' }}
          </span>
        </div>
      </div>

      <button 
        class="delete-btn" 
        @click="handleDelete"
        :disabled="!fileName"
        :class="{ 'disabled': !fileName }"
      >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="trash-icon">
          <polyline points="3 6 5 6 21 6"></polyline>
          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
        </svg>
      </button>

    </div>
  </div>
</template>

<script>
export default {
  name: 'UploadedFileCard',
  props: {
    // Parent passes the filename here. 
    // If null/empty, component shows "Missing" state.
    fileName: {
      type: String,
      default: null 
    }
  },
  methods: {
    handleDelete() {
      // Logic: Only emit delete if there is actually a file to delete
      if (this.fileName) {
        this.$emit('delete-file');
      }
    }
  }
}
</script>

<style scoped>
.file-card-wrapper {
  max-width: 600px;
  margin: 20px auto;
  font-family: 'Arial', sans-serif;
}

.section-label {
  font-size: 1rem;
  font-weight: 800;
  margin-bottom: 10px;
  color: #000;
}

/* --- Card Container --- */
.status-card {
  border: 2px solid #A03030; /* Dark Red Border */
  border-radius: 15px;
  padding: 15px 25px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #FFFFFF;
  transition: background-color 0.3s;
}

.left-section {
  display: flex;
  align-items: center;
  gap: 20px;
}

/* --- Icons --- */
.folder-icon {
  width: 32px;
  height: 32px;
  stroke: #1F2937; /* Dark Grey/Black */
}

.trash-icon {
  width: 24px;
  height: 24px;
  stroke: #1F2937;
}

/* --- Typography --- */
.text-info {
  display: flex;
  flex-direction: column;
}

.file-name {
  font-family: 'Courier New', Courier, monospace; /* Monospace for file look */
  font-weight: 700;
  font-size: 1rem;
  color: #000;
}

.status-text {
  font-family: 'Courier New', Courier, monospace;
  font-size: 0.8rem;
  margin-top: 4px;
  text-transform: uppercase;
}

/* Dynamic Status Colors */
.status-missing {
  color: #757575; /* Grey for missing */
}

.status-ok {
  color: #2E7D32; /* Green for success/uploaded */
}

/* --- Delete Button --- */
.delete-btn {
  background: none;
  border: none;
  cursor: pointer;
  padding: 8px;
  border-radius: 50%;
  transition: background-color 0.2s;
}

.delete-btn:hover {
  background-color: #FEE2E2; /* Light red hover */
  stroke: #B91C1C;
}

/* Disabled state for delete button (when no file exists) */
.delete-btn.disabled {
  opacity: 0.3;
  cursor: not-allowed;
}

.delete-btn.disabled:hover {
  background-color: transparent;
}
</style>