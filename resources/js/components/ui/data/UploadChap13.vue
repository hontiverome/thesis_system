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
        <p class="format-text">{{ acceptedFormatText }}</p>

        <button class="browse-btn" @click.stop="triggerFileInput">
          Browse File
        </button>

        <input 
          type="file" 
          ref="fileInput" 
          class="hidden-input" 
          :accept="accept" 
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
    // Dynamic Title passed from Parent
    title: {
      type: String,
      required: true
    },
    // Dynamic Subtitle
    subtitle: {
      type: String,
      default: 'Select and upload your file'
    },
    // Allows parent to control file types (e.g., ".pdf,.docx")
    accept: {
      type: String,
      default: '.pdf'
    },
    // Descriptive text for the UI
    acceptedFormatText: {
      type: String,
      default: 'PDF format only'
    }
  },
  methods: {
    triggerFileInput() {
      this.$refs.fileInput.click();
    },
    handleFileSelected(event) {
      const file = event.target.files[0];
      if (file) {
        this.processFile(file);
      }
    },
    handleDrop(event) {
      const file = event.dataTransfer.files[0];
      if (file) {
        this.processFile(file);
      }
    },
    processFile(file) {
      this.$emit('file-uploaded', file);
      // Reset input so the same file can be selected again if needed
      this.$refs.fileInput.value = null;
    }
  }
}
</script>

<style scoped>
.upload-card {
  border: 2px solid #A03030;
  border-radius: 20px;
  background-color: #FFFFFF;
  font-family: 'Arial', sans-serif;
  max-width: 600px;
  margin: 20px auto;
  overflow: hidden;
}

.card-header {
  display: flex;
  align-items: center;
  padding: 20px 25px;
  border-bottom: 1px solid #E0E0E0;
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
  flex-shrink: 0;
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
  color: #666;
  margin: 2px 0 0 0;
}

.card-body {
  padding: 30px;
}

.dashed-zone {
  border: 2px dashed #A03030;
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
  background-color: #FFF5F5;
}

.body-icon {
  width: 24px;
  height: 24px;
  margin-bottom: 15px;
  stroke: #333;
}

.instruction-text {
  font-family: 'Courier New', Courier, monospace;
  font-weight: 700;
  font-size: 1rem;
  color: #000;
  margin: 0 0 5px 0;
}

.format-text {
  font-family: 'Courier New', Courier, monospace;
  font-size: 0.9rem;
  color: #888;
  margin: 0 0 20px 0;
}

.browse-btn {
  background-color: #FFFFFF;
  border: 1px solid #000;
  border-radius: 20px;
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