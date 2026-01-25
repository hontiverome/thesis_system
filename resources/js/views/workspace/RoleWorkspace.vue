// RoleWorkspace.vue - Workspace LAYOUT for different user roles (student, admin, adviser, faculty)

<template>
  <div class="workspace-container">
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
const { course, tab, courseTitle } = useWorkspaceApi(); 
</script>

<style scoped>
.workspace-container {
  background-color: var(--bg-color); 
  color: var(--text-color);
  min-height: 100vh;
}

/* Header styles kept in case you want to uncomment later */
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