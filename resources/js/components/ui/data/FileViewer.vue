<template>
  <div class="pdf-viewer-container">
    
    <div class="viewer-header">
      <div class="file-info">
        <svg xmlns="http://www.w3.org/2000/svg" class="file-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
        </svg>
        <span class="file-name-text">{{ fileName }}</span>
      </div>

      <div class="controls-wrapper">
        <div class="zoom-pill">
          <button class="zoom-btn" @click="zoomOut" :disabled="zoomLevel <= 50">−</button>
          <span class="zoom-display">{{ zoomLevel }}%</span>
          <button class="zoom-btn" @click="zoomIn" :disabled="zoomLevel >= 200">+</button>
        </div>
      </div>

      <div class="action-group">
        <button class="download-circle-btn" @click="$emit('download-file')" title="Download PDF">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="7 10 12 15 17 10"></polyline>
            <line x1="12" y1="15" x2="12" y2="3"></line>
          </svg>
        </button>
      </div>
    </div>

    <div class="workspace">
      <div v-if="fileUrl" class="document-scaling-container" :style="zoomStyle">
        <iframe 
          :src="fileUrl + '#toolbar=0&navpanes=0&scrollbar=0'" 
          width="100%" 
          height="100%" 
          frameborder="0"
          class="pdf-iframe"
        ></iframe>
      </div>
      
      <div v-else class="no-file-state">
        <p>No document selected for preview.</p>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  name: 'FileViewer',
  props: {
    fileName: {
      type: String,
      default: 'Document.pdf'
    },
    fileUrl: {
      type: String,
      default: null
    }
  },
  data() {
    return {
      zoomLevel: 100
    }
  },
  computed: {
    zoomStyle() {
      return {
        transform: `scale(${this.zoomLevel / 100})`,
        transformOrigin: 'top center',
        width: '100%',
        height: '100%'
      }
    }
  },
  methods: {
    zoomIn() {
      if (this.zoomLevel < 200) this.zoomLevel += 10;
    },
    zoomOut() {
      if (this.zoomLevel > 50) this.zoomLevel -= 10;
    }
  }
}
</script>

<style scoped>
/* --- Main Container Adjustments --- */
.pdf-viewer-container {
  display: flex;
  flex-direction: column;
  /* REDUCE HEIGHT HERE */
  height: 750px; 
  /* REDUCE WIDTH HERE */
  max-width: 600px; 
  margin: 20px auto;
  background-color: #1a1a1b;
  /* REMOVE ROUNDED CORNERS */
  border-radius: 0; 
  border: 1px solid #333;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0,0,0,0.4);
}

.viewer-header {
  background-color: #272729;
  height: 60px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 24px;
  border-bottom: 1px solid #3e3e42;
  z-index: 10;
}

.file-info { flex: 1; display: flex; align-items: center; gap: 12px; color: #efefef; }
.file-icon { width: 20px; color: #A03030; }
.file-name-text { font-size: 0.95rem; font-weight: 500; }

.controls-wrapper { flex: 1; display: flex; justify-content: center; }

.zoom-pill {
  display: flex;
  align-items: center;
  background: #1a1a1b;
  border: 1px solid #444;
  border-radius: 30px;
  padding: 4px 12px;
}

.zoom-btn {
  background: none;
  border: none;
  color: #aaa;
  font-size: 1.2rem;
  cursor: pointer;
  padding: 0 10px;
  transition: color 0.2s;
}

.zoom-btn:hover:not(:disabled) { color: #fff; }
.zoom-btn:disabled { opacity: 0.2; cursor: not-allowed; }

.zoom-display {
  color: #fff;
  font-size: 0.85rem;
  font-weight: 600;
  min-width: 50px;
  text-align: center;
}

.action-group { flex: 1; display: flex; justify-content: flex-end; }

.download-circle-btn {
  background: #A03030;
  color: white;
  border: none;
  width: 38px;
  height: 38px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
}

.workspace {
  flex-grow: 1;
  background: #525659;
  overflow: auto;
  position: relative;
}

.document-scaling-container {
  transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.pdf-iframe {
  display: block;
}

.no-file-state { 
  display: flex;
  height: 100%;
  width: 100%;
  justify-content: center;
  align-items: center;
  color: #fff; 
  opacity: 0.5; 
  font-family: sans-serif; 
}
</style>