<template>
  <div class="upload-card">
    
    <div class="card-header">
      <div class="icon-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="header-icon">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
          <line x1="12" y1="18" x2="12" y2="12"></line>
          <polyline points="9 15 12 12 15 15"></polyline>
        </svg>
      </div>
      
      <div class="header-text">
        <h3 class="card-title">{{ title }}</h3>
        <p class="card-subtitle">{{ subtitle }}</p>
      </div>
    </div>

    <div class="card-body">
      <div 
        class="dashed-zone" 
        @click="triggerFileInput"
        @dragover.prevent 
        @drop.prevent="handleDrop"
      >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="body-icon">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
          <line x1="12" y1="18" x2="12" y2="12"></line>
          <polyline points="9 15 12 12 15 15"></polyline>
        </svg>

        <p class="instruction-text">Choose a file or drag & drop it here</p>
        <p class="format-text">PDF format only</p>

        <button class="browse-btn" @click.stop="triggerFileInput">
          Browse File
        </button>

        <input 
          type="file" 
          ref="fileInput" 
          class="hidden-input" 
          accept=".pdf" 
          @change="handleFileSelected"
        />
      </div>
    </div>

  </div>
</template>

<script>
export default {
  name: 'FileUploadCard',
  props: {
    title: {
      type: String,
      default: 'Upload Chapters 1 - 3 (MOR)'
    },
    subtitle: {
      type: String,
      default: 'Select and upload your file'
    }
  },
  methods: {
    // 1. Opens the File Manager
    triggerFileInput() {
      this.$refs.fileInput.click();
    },
    // 2. Handles file selection via "Browse" button
    handleFileSelected(event) {
      const file = event.target.files[0];
      if (file) {
        this.processFile(file);
      }
    },
    // 3. Handles Drag and Drop functionality
    handleDrop(event) {
      const file = event.dataTransfer.files[0];
      if (file) {
        this.processFile(file);
      }
    },
    // 4. Emits the file to the parent component
    processFile(file) {
      console.log("File selected:", file.name);
      this.$emit('file-uploaded', file);
    }
  }
}
</script>

<style scoped>
/* --- Outer Card Styling --- */
.upload-card {
  border: 2px solid #A03030; /* Dark Red/Brown Border */
  border-radius: 20px;
  background-color: #FFFFFF;
  font-family: 'Arial', sans-serif;
  max-width: 600px;
  margin: 20px auto;
  overflow: hidden; /* Ensures child elements respect border radius */
}

/* --- Header Section --- */
.card-header {
  display: flex;
  align-items: center;
  padding: 20px 25px;
  border-bottom: 1px solid #333; /* The solid line separator */
  gap: 15px;
}

.icon-wrapper {
  width: 40px;
  height: 40px;
  border: 1.5px solid #000;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.header-icon {
  width: 20px;
  height: 20px;
  stroke: #000;
}

.header-text {
  display: flex;
  flex-direction: column;
}

.card-title {
  font-size: 1rem;
  font-weight: 700;
  color: #000;
  margin: 0;
}

.card-subtitle {
  font-size: 0.85rem;
  color: #333;
  margin: 2px 0 0 0;
}

/* --- Body Section --- */
.card-body {
  padding: 30px;
}

.dashed-zone {
  border: 2px dashed #A03030; /* Matches outer border color */
  border-radius: 20px;
  padding: 30px;
  text-align: center;
  cursor: pointer;
  transition: background-color 0.2s;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.dashed-zone:hover {
  background-color: #FFF5F5; /* Very light red hover tint */
}

.body-icon {
  width: 24px;
  height: 24px;
  margin-bottom: 15px;
  stroke: #333;
}

.instruction-text {
  font-family: 'Courier New', Courier, monospace; /* Typewriter font style */
  font-weight: 700;
  font-size: 1rem;
  color: #000;
  margin: 0 0 5px 0;
}

.format-text {
  font-family: 'Courier New', Courier, monospace;
  font-size: 0.9rem;
  color: #888; /* Grey text */
  margin: 0 0 20px 0;
}

/* --- Browse Button --- */
.browse-btn {
  background-color: #FFFFFF;
  border: 1px solid #000;
  border-radius: 20px; /* Pill shape */
  padding: 8px 25px;
  font-family: 'Courier New', Courier, monospace;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.2s;
}

.browse-btn:hover {
  background-color: #F0F0F0;
}

.hidden-input {
  display: none;
}
</style>