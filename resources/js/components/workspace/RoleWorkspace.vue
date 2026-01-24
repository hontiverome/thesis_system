<template>
  <div class="workspace-container">
    <header class="workspace-header">
      <div class="title-meta">
        <h1 class="university-red">{{ courseTitle?.toUpperCase() }}</h1>
        <h2 class="tab-title">{{ tab?.replace(/-/g, ' ').toUpperCase() }}</h2>
      </div>
      <div class="header-actions">
        <slot name="actions"></slot>
      </div>
    </header>

    <main class="workspace-content">
      <slot></slot> 
      
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>
  </div>
</template>

<script setup>
import { useWorkspaceApi } from '@/composables/useWorkspaceApi';
// Pulling inherited context and the pretty title from our configuration
const { course, tab, courseTitle } = useWorkspaceApi(); 
</script>

<style scoped>
.university-red { 
  color: #800000; 
  margin: 0; 
  font-size: 1.8rem;
  font-weight: bold;
}
.tab-title {
  font-size: 1.1rem;
  color: #555;
  margin-top: 5px;
}
.workspace-header { 
  border-bottom: 2px solid #FFA500; 
  padding-bottom: 15px; 
  margin-bottom: 20px;
  display: flex; 
  justify-content: space-between; 
  align-items: flex-end;
}
.workspace-content {
  min-height: 300px;
}

/* Smooth transition between tabs */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>