// RoleWorkspace.vue - Workspace layout for different user roles (student, admin, adviser, faculty)

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
.workspace-container {
  background-color: var(--bg-color); 
  color: var(--text-color);
  min-height: 100vh;
}

.workspace-header {
  border-bottom: 2px solid var(--border-color); 
  padding: 1.5rem;
  background: var(--header-bg);
}

.university-red {
  color: var(--primary-color); 
}
.overview-template {
  padding: 2rem;
  background-color: var(--bg-color);
}

:deep(.base-card) {
  background-color: var(--input-bg);
  border: 1px solid var(--border-color);
  color: var(--text-color);
}
</style>