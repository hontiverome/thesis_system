<template>
  <aside class="sidebar-container">
    
    <div class="sidebar-header">
      <h3>Courses</h3>
    </div>

    <div class="course-card">
      <div class="course-title">MOR</div>
      
      <nav class="course-nav">
        <a href="#" 
           class="nav-item" 
           :class="{ active: activeItem === 'PROPOSAL' }"
           @click.prevent="setActive('PROPOSAL')">
          PROPOSAL
        </a>
        
        <a href="#" 
           class="nav-item" 
           :class="{ active: activeItem === 'CHAPTER 1' }"
           @click.prevent="setActive('CHAPTER 1')">
          CHAPTER 1
        </a>

        <a href="#" 
           class="nav-item" 
           :class="{ active: activeItem === 'CHAPTER 2' }"
           @click.prevent="setActive('CHAPTER 2')">
          CHAPTER 2
        </a>

        <a href="#" 
           class="nav-item" 
           :class="{ active: activeItem === 'CHAPTER 3' }"
           @click.prevent="setActive('CHAPTER 3')">
          CHAPTER 3
        </a>

        <a href="#" 
           class="nav-item" 
           :class="{ active: activeItem === 'OTHERS' }"
           @click.prevent="setActive('OTHERS')">
          OTHERS
        </a>
      </nav>
    </div>

  </aside>
</template>

<script setup>
import { ref } from 'vue';

// 1. Create a variable to track which item is active. 
// Default is 'PROPOSAL'
const activeItem = ref('PROPOSAL');

// 2. Function to update the active item when clicked
const setActive = (itemName) => {
  activeItem.value = itemName;
  // logic to navigate to the actual page would go here later
  // e.g., router.push(...)
};
</script>

<style scoped>
.sidebar-container {
  width: 260px;
  height: 100vh;
  background-color: white;
  padding: 20px;
  box-sizing: border-box;
  font-family: 'Courier New', Courier, monospace;
  position: fixed;
  top: 80px;
  left: 0;
  overflow-y: auto;
  box-shadow: 2px 0 5px rgba(0,0,0,0.05);
}

.sidebar-header {
  margin-bottom: 20px;
  padding-bottom: 5px;
}

.sidebar-header h3 {
  margin: 0;
  font-size: 1.2rem;
  font-weight: bold;
  text-transform: capitalize;
  letter-spacing: 1px;
}

.course-card {
  padding: 0;
  margin-bottom: 20px;
  background-color: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.course-title {
  text-align: center;
  font-size: 1.5rem;
  font-weight: bold;
  color: #800000;
  padding: 15px 0;
  background-color: white;
  letter-spacing: 2px;
}

.course-nav {
  display: flex;
  flex-direction: column;
}

.nav-item {
  text-decoration: none;
  font-size: 0.85rem;
  font-weight: bold;
  text-transform: uppercase;
  padding: 10px 0;
  text-align: center;
  color: black;
  background-color: #f0f4f5;
  margin-bottom: 1px;
  transition: background-color 0.2s;
  cursor: pointer; /* Ensures it looks clickable */
}

/* ACTIVE STATE: This applies when the class "active" is present */
.nav-item.active {
  background-color: #800000;
  color: white;
  margin-bottom: 0;
}

.nav-item:hover:not(.active) {
  background-color: #e0e4e5;
}

@media (max-width: 768px) {
  .sidebar-container {
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    z-index: 999;
  }
  
  .sidebar-container.open {
    transform: translateX(0);
    box-shadow: 5px 0 15px rgba(0,0,0,0.2);
  }
}
</style>