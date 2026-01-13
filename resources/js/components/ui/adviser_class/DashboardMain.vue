<template>
  <div class="dashboard-root">
    <div class="tabs-row">
      <div 
        v-for="tab in ['groups', 'enrollees']" 
        :key="tab"
        :class="['tab', { active: activeTab === tab }]" 
        @click="activeTab = tab"
      >
        {{ tab.toUpperCase() }}
      </div>
    </div>

    <div class="main-card">
      <GroupManager 
        v-if="activeTab === 'groups'" 
        :class-section="classSection" 
      />
      <EnrolleeManager v-else />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import GroupManager from './GroupManager.vue';
import EnrolleeManager from './EnrolleeManager.vue';

// Define props so other developers can pass data into this component
const props = defineProps({
  classSection: {
    type: String,
    default: '' // Default value if no prop is provided
  }
});

const activeTab = ref('groups');
</script>

<style scoped>
.dashboard-root { padding: 50px 20px; font-family: 'Inter', sans-serif; background: #fff; }
.tabs-row { display: flex; margin-left: 20px; }
.tab { 
  padding: 12px 35px; cursor: pointer; font-weight: 700; color: #888; letter-spacing: 1px;
  border: 1px solid transparent; transition: 0.3s;
}
.tab.active { 
  background: #F4F6F9; color: #333; border-radius: 10px 10px 0 0; 
  border: 1px solid #D9D9D9; border-bottom-color: #F4F6F9; margin-bottom: -1px; z-index: 2;
}
.main-card { 
  background: #F4F6F9; border-radius: 12px; border-top-left-radius: 0; 
  padding: 40px; border: 1px solid #D9D9D9; box-shadow: 0 10px 30px rgba(0,0,0,0.03);
}
</style>