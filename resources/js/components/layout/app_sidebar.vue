<template>
  <aside class="sidebar-container">
    <div class="sidebar-label">Courses</div>

    <div class="course-section">
      <div class="course-header" @click="toggleDropdown">
        
        <div class="header-col left">
          <span class="home-icon" v-html="homeIcon"></span>
        </div>

        <div class="header-col center">
          <h2 class="course-title">MOR</h2>
        </div>
        
        <div class="header-col right">
          <div class="header-controls">
            <span class="chevron" :class="{ rotated: !isOpen }">
              <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"/>
              </svg>
            </span>
            <div class="vertical-divider" :class="{ active: isOpen }"></div>
          </div>
        </div>
      </div>

      <nav v-show="isOpen" class="course-nav">
        <button 
          class="nav-btn" 
          @click="setActive('PROPOSAL')"
          :class="{ 'btn-active': activeItem === 'PROPOSAL', 'btn-inactive': activeItem !== 'PROPOSAL' }">
          PROPOSAL
        </button>
        
        <button 
          class="nav-btn" 
          @click="setActive('CHAPTERS 1-3')"
          :class="{ 'btn-active': activeItem === 'CHAPTERS 1-3', 'btn-inactive': activeItem !== 'CHAPTERS 1-3' }">
          CHAPTERS 1-3
        </button>
      </nav>
    </div>
  </aside>
</template>

<script setup>
import { ref, defineProps } from 'vue';

const props = defineProps({
  homeIcon: {
    type: String,
    default: `<svg viewBox="0 0 24 24" width="32" height="32" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>`
  }
});

const isOpen = ref(true);
const activeItem = ref('PROPOSAL');

const toggleDropdown = () => {
  isOpen.value = !isOpen.value;
};

const setActive = (val) => {
  activeItem.value = val;
};
</script>

<style scoped>
.sidebar-container {
  width: 280px;
  height: 100vh;
  background-color: #fff;
  padding: 20px 0px 20px 20px;
  position: fixed;
  top: 80px; 
  left: 0;
  z-index: 100;
  box-sizing: border-box;
  font-family: sans-serif;
}

.sidebar-label {
  text-align: left;
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: 25px;
  color: #333;
}

/* Header Container */
.course-header {
  display: flex;
  width: 100%;
  align-items: center;
  cursor: pointer;
  margin-bottom: 20px;
}

/* The Secret to Centering: Three Equal Columns */
.header-col {
  flex: 1; /* Each takes 33.3% of the width */
  display: flex;
  align-items: center;
}

.header-col.left {
  justify-content: flex-start;
}

.header-col.center {
  justify-content: center;
}

.header-col.right {
  justify-content: flex-end;
}

.home-icon {
  color: #800000;
  display: flex;
  align-items: center;
}

.course-title {
  margin: 0;
  color: #800000;
  font-size: 1.8rem;
  font-weight: 800;
  letter-spacing: 2px;
  line-height: 1;
}

.header-controls {
  display: flex;
  align-items: center;
  gap: 10px;
}

.chevron {
  transition: transform 0.3s ease;
  color: #444;
}

.chevron.rotated {
  transform: rotate(180deg);
}

.vertical-divider {
  width: 4px;
  height: 35px;
  background-color: transparent;
  transition: background-color 0.3s ease;
}

.vertical-divider.active {
  background-color: #800000;
}

.course-nav {
  display: flex;
  flex-direction: column;
  gap: 10px;
  padding-right: 20px;
}

.nav-btn {
  border: none;
  border-radius: 8px;
  padding: 12px;
  font-weight: bold;
  font-size: 0.9rem;
  cursor: pointer;
  width: 100%;
  transition: all 0.2s ease;
}

.btn-active {
  background-color: #800000;
  color: white;
}

.btn-inactive {
  background-color: #f1f4f8;
  color: #000;
}

.nav-btn:hover {
  opacity: 0.9;
}
</style>