<template>
  <div>
    <div class="tabs-row">
      <div v-for="tab in tabs" :key="tab.path" 
           :class="{ active: isActiveTab(tab) }" 
           @click="goToTab(tab)">
        {{ formatTab(tab.path) }}
      </div>
    </div>
    <router-view />
  </div>
</template>
  
<script setup>
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

const tabs = computed(() => route.matched[route.matched.length - 1].children || []);
const isActiveTab = (tab) => route.path.endsWith(tab.path);
const goToTab = (tab) => {
  // If tab path is already in current route, don't duplicate
  if (route.path.endsWith(tab.path)) {
    return;
  }
  router.push({ path: `${route.path}/${tab.path}` });
};
const formatTab = (path) => path.replace(/-/g,' ').toUpperCase();
</script>
