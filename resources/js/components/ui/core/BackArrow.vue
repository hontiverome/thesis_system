<template>
  <div class="rounded-card">
    
    <div class="header-row">
      <div class="header-left">
        <button 
          v-if="showBackArrow" 
          class="back-button" 
          @click="$emit('back')"
          title="Go Back"
        >
          <Icon icon="iconamoon:arrow-left-2-light" />
        </button>
        <h2 class="title-header">{{ title }}</h2>
      </div>
      
      <span class="deadline-text">{{ deadline }}</span>
    </div>
    
    <div class="card-content">
      <slot></slot>
    </div>

  </div>
</template>

<script>
import { Icon } from '@iconify/vue';

export default {
  name: 'RoundedCard',
  components: {
    Icon
  },
  props: {
    title: {
      type: String,
      default: 'SAMPLE TITLE'
    },
    deadline: {
      type: String,
      default: 'STATUS: ---'
    },
    // Prop to control visibility of the arrow
    showBackArrow: {
      type: Boolean,
      default: true
    }
  }
}
</script>

<style scoped>
.rounded-card {
  background-color: #FFFFFF;
  border-radius: 20px;
  padding: 40px;
  max-width: 800px;
  margin: 20px auto;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  font-family: 'Arial', sans-serif;
}

.header-row {
  display: flex;
  justify-content: space-between;
  align-items: center; 
  margin-bottom: 30px;
}

/* Grouping Arrow and Title */
.header-left {
  display: flex;
  align-items: center;
  gap: 15px; /* Space between arrow and text */
}

/* Transparent button for the icon */
.back-button {
  background: none;
  border: none;
  padding: 0;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #000;
  font-size: 2rem; /* Size of the < arrow */
  transition: transform 0.2s ease, color 0.2s ease;
}

.back-button:hover {
  color: #D32F2F; /* Turns red on hover to match deadline */
  transform: translateX(-3px); /* Subtle nudge to the left */
}

.title-header {
  font-size: 1.5rem;      
  font-weight: 900;        
  color: #000000;          
  text-transform: uppercase;
  letter-spacing: 2px;    
  margin: 0;              
}

.deadline-text {
  font-size: 0.9rem;
  font-weight: bold;
  color: #D32F2F;          
  text-transform: uppercase;
  letter-spacing: 1px;
}

.card-content {
  width: 100%;
}
</style>