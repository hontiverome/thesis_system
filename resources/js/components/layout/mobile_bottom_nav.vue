<template>
  <nav class="mobile-bottom-nav">
    <router-link
      v-for="item in navItems"
      :key="item.path"
      :to="item.path"
      class="mbnav-link"
    >
      <IconifyIcon :icon="item.icon" class="mbnav-icon" />
    </router-link>
  </nav>
</template>

<script setup>
import { onMounted } from 'vue';
import { loadIcons } from '@iconify/vue';

const navItems = [
  { path: '/home', text: 'Home', icon: 'mdi:home' },
  { path: '/dashboard', text: 'Dashboard', icon: 'mdi:view-dashboard' },
  { path: '/profile', text: 'Profile', icon: 'mdi:account' },
  { path: '/settings', text: 'Settings', icon: 'mdi:cog' },
  { path: '/help', text: 'Help', icon: 'mdi:help-circle' },
];

onMounted(() => {
  loadIcons(['mdi:home','mdi:view-dashboard','mdi:account','mdi:cog','mdi:help-circle']);
});
</script>

<style scoped>
.mbnav-link {
  display: inline-flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: var(--sidebar-text);
  text-decoration: none;
  padding: 0.25rem 0.5rem;
  min-width: 64px;
}
.mbnav-link.router-link-active {
  color: var(--header-active-text);
}
.mbnav-icon {
  font-size: 24px;
}
</style>