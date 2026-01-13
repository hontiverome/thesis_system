<template>
  <div class="rounded-card" :style="{ maxWidth: maxWidth }">
    
    <div class="header-row">
      <div class="header-left">
        <button 
          v-if="showBackArrow" 
          class="back-button" 
          @click="$emit('back')"
        >
          <Icon :icon="backIcon" />
        </button>
        <h2 class="title-header">{{ title }}</h2>
      </div>
      
      <div class="header-right">
        <slot name="header-right"></slot>
      </div>
    </div>
    
    <div class="card-content">
      <slot></slot>
    </div>

  </div>
</template>

<script setup>
import { Icon } from '@iconify/vue';

const props = defineProps({
  title: { 
    type: String, 
    default: '' 
  },
  showBackArrow: { 
    type: Boolean, 
    default: true 
  },
  backIcon: { 
    type: String, 
    default: 'iconamoon:arrow-left-2-light' 
  },
  maxWidth: { 
    type: String, 
    default: '800px' 
  }
});

defineEmits(['back']);
</script>

<style scoped>
.rounded-card {
  background-color: #FFFFFF;
  border-radius: 20px;
  padding: 40px;
  margin: 20px auto;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  font-family: inherit;
}

.header-row {
  display: flex;
  justify-content: space-between;
  align-items: center; 
  margin-bottom: 30px;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 15px;
}

.back-button {
  background: none;
  border: none;
  padding: 0;
  cursor: pointer;
  display: flex;
  font-size: 2rem;
  color: #000;
  transition: transform 0.2s ease;
}

.back-button:hover {
  transform: translateX(-3px);
}

.title-header {
  font-size: 1.5rem;      
  font-weight: 900;                
  text-transform: uppercase;
  margin: 0;              
}

.header-right {
  display: flex;
  align-items: center;
}

.card-content {
  width: 100%;
}
</style>