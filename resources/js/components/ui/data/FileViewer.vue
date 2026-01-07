<template>
  <div class="pdf-viewer-container">
    
    <div class="toolbar">
      <div class="tool-group">
        <span class="page-count">1 / 30</span>
      </div>

      <div class="tool-group zoom-controls">
        <button class="icon-btn" @click="zoomOut">-</button>
        <span class="zoom-level">{{ zoomLevel }}%</span>
        <button class="icon-btn" @click="zoomIn">+</button>
      </div>

      <div class="tool-group">
        <button class="icon-btn" @click="triggerDownload">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
        </button>
      </div>
    </div>

    <div class="scroll-area">
      <div class="document-page" :style="paperStyle">
        <h1 class="doc-title">Lorem ipsum</h1>
        <h3 class="doc-subtitle">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc ac faucibus odio.
        </h3>
        <p class="doc-text">
          Vestibulum neque massa, scelerisque sit amet ligula eu, congue molestie mi. Praesent ut varius sem. Nullam at porttitor arcu, nec lacinia nisi. Ut ac dolor vitae odio interdum condimentum.
        </p>
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
    }
  },
  data() {
    return {
      zoomLevel: 100
    }
  },
  computed: {
    paperStyle() {
      const baseWidth = 450; 
      const currentWidth = baseWidth * (this.zoomLevel / 100);
      return {
        width: `${currentWidth}px`,
        fontSize: `${this.zoomLevel}%`
      }
    }
  },
  methods: {
    zoomIn() {
      if (this.zoomLevel < 200) this.zoomLevel += 10;
    },
    zoomOut() {
      if (this.zoomLevel > 50) this.zoomLevel -= 10;
    },
    
    // --- NEW METHOD FOR DOWNLOAD BUTTON ---
    triggerDownload() {
      // 1. Log it so you can see it working in the Console (F12)
      console.log("Download button clicked!");
      
      // 2. OPTIONAL: Show an alert so you know it worked (Remove this later)
      alert(`Downloading ${this.fileName}...`);

      // 3. Emit the signal to the Parent Page
      // This tells the backend developer: "Hey, the user wants this file!"
      this.$emit('download-file');
    }
  }
}
</script>

<style scoped>
/* (Keep your styles exactly the same as the previous correct version) */
.pdf-viewer-container {
  display: flex;
  flex-direction: column;
  height: 600px;
  max-width: 800px;
  margin: 0 auto;
  border: 1px solid #ccc;
  font-family: 'Arial', sans-serif;
  background-color: #525659;
}

.toolbar {
  background-color: #323639;
  color: #f1f1f1;
  height: 50px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 15px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.2);
  z-index: 10;
  flex-shrink: 0; 
}

.tool-group {
  display: flex;
  align-items: center;
  gap: 10px;
}

.page-count { font-size: 0.85rem; }

.zoom-controls {
  background-color: #000000;
  padding: 2px 8px;
  border-radius: 4px;
  user-select: none;
}

.zoom-level { margin: 0 8px; font-size: 0.8rem; min-width: 40px; text-align: center; }

.icon-btn {
  background: none;
  border: none;
  color: #f1f1f1;
  font-size: 1.1rem;
  cursor: pointer;
  padding: 0 5px;
}

.icon-btn:hover { color: #fff; }

.scroll-area {
  background-color: #525659;
  flex-grow: 1;
  overflow: auto; 
  padding: 30px;
  display: block; 
}

.document-page {
  background-color: white;
  margin: 0 auto; 
  min-height: 800px;
  padding: 50px; 
  box-shadow: 0 4px 15px rgba(0,0,0,0.3);
  color: #333;
  transition: width 0.2s ease, font-size 0.2s ease;
}

.doc-title {
  text-align: center;
  font-size: 2em; 
  margin-bottom: 0.5em;
  font-weight: bold;
}

.doc-subtitle {
  text-align: center;
  font-size: 1.1em;
  margin-bottom: 2em;
  color: #555;
}

.doc-text {
  font-size: 0.9em;
  line-height: 1.6;
  margin-bottom: 1em;
  text-align: justify;
}
</style>