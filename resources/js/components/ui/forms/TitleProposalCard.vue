<template>
  <div class="proposal-item">
    
    <div class="left-content">
      <svg class="star-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
      </svg>
      <span class="title-text">{{ title }}</span>
    </div>

    <a href="#" class="upload-link" @click.prevent="triggerFileInput">
      <span>Upload</span>
      <svg class="arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="9 18 15 12 9 6"></polyline>
      </svg>
    </a>

    <input 
      type="file" 
      ref="fileInput" 
      style="display: none" 
      @change="handleFileChange"
    />

  </div>
</template>

<script>
export default {
  name: 'ProposalItem',
  props: {
    title: {
      type: String,
      default: 'Title'
    }
  },
  methods: {
    // This connects the visible text to the hidden input
    triggerFileInput() {
      this.$refs.fileInput.click();
    },
    // This catches the file once the user selects it
    handleFileChange(event) {
      const file = event.target.files[0];
      if (file) {
        // Emit the file up to the parent component
        // This is where your job ends and the Logic Developer's job begins
        this.$emit('file-selected', file);
      }
    }
  }
}
</script>

<style scoped>
/* Your existing styles remain exactly the same */
.proposal-item {
  background-color: #F4F6F9;
  border-radius: 8px;
  padding: 15px 20px;
  margin-bottom: 10px;
  display: flex;
  justify-content: space-between; 
  align-items: center;
  font-family: 'Arial', sans-serif;
}

.left-content {
  display: flex;
  align-items: center;
  gap: 15px; 
}

.star-icon {
  width: 20px;
  height: 20px;
  stroke: #B71C1C; 
}

.title-text {
  color: #333333;
  font-size: 15px;
  font-weight: 500;
}

.upload-link {
  display: flex;
  align-items: center;
  gap: 8px; 
  text-decoration: none;
  color: #9CA3AF; 
  font-size: 14px;
  cursor: pointer;
  transition: color 0.2s;
}

.upload-link:hover {
  color: #6B7280; 
}

.arrow-icon {
  width: 16px;
  height: 16px;
}
</style>