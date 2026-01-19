<template>
  <div class="group-manager-root">
    <div class="header-actions">
      <button 
        :class="['btn-toggle', { active: activeView === 'list' }]" 
        @click="activeView = 'list'"
      >
        <Icon icon="lucide:users" v-if="activeView === 'list'" /> Group List
      </button>
      <button 
        :class="['btn-toggle', { active: activeView === 'submission' }]" 
        @click="activeView = 'submission'"
      >
        <Icon icon="lucide:file-text" v-if="activeView === 'submission'" /> View Group Submission
      </button>
    </div>

    <GroupList v-if="activeView === 'list'" :class-section="classSection" />
    <GroupSubmission v-else :class-section="classSection" />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Icon } from '@iconify/vue';
import GroupList from './GroupList.vue';
import GroupSubmission from './GroupSubmission.vue';

defineProps(['classSection']);
const activeView = ref('list');
</script>

<style scoped>
.header-actions { display: flex; justify-content: flex-end; gap: 15px; margin-bottom: 30px; }
.btn-toggle { 
  background: transparent; border: none; padding: 10px 20px; border-radius: 8px;
  font-weight: 700; color: #94a3b8; cursor: pointer; display: flex; align-items: center; gap: 8px;
}
.btn-toggle.active { background: #EDC35E; color: #fff; box-shadow: 0 4px 10px rgba(237, 195, 94, 0.3); }
</style>