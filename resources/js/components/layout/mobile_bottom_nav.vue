<!---
 * System Name: Theming and UI Framework
 * Component: Mobile Bottom Navigation
 * Purpose Of this file:
 * Provides the bottom navigation for navbar-only layout on mobile devices.
 * 
 * Author: Jerome Andrei O. Hontiveros
 * Copyright (C) 2025
 * by the Department of Science and Technology — Project LODI
 * All rights reserved.
 * 
 * Permission is hereby granted, free of charge, to any persons obtaining a copy
 * of this software and associated documentation files, to deal in the Software
 * without restriction, including the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, provided that the
 * above copyright notice(s) and this permission notice appears in all copies of
 * the Software and that both the above copyright notice(s) and this permission
 * notice appear in supporting documentation.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT OF THIRD PARTY RIGHTS.
 * IN NO EVENT SHALL THE COPYRIGHT HOLDER OR HOLDERS INCLUDED IN THIS NOTICE BE
 * LIABLE FOR ANY CLAIM, OR ANY SPECIAL INDIRECT OR CONSEQUENTIAL DAMAGES, OR ANY
 * DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
 * ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF OR IN
 * CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 * 
 * Except as contained in this notice, the name of a copyright holder shall not
 * be used in advertising or otherwise to promote the sale, use or other dealings
 * in this Software without prior written authorization of the copyright holder.
-->
<template>
  <nav class="mobile-bottom-nav" aria-label="Mobile navigation">
    <router-link
      v-for="item in navItems"
      :key="item.path"
      :to="item.path"
      class="mbnav-link"
      :title="item.text"
      :aria-label="item.text"
    >
      <IconifyIcon :icon="item.icon" class="mbnav-icon" aria-hidden="true" />
    </router-link>
  </nav>
</template>

<script setup>
import { onMounted } from 'vue';
import { loadIcons } from '@iconify/vue';

// Navigation items
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
  gap: 0.25rem;
  color: var(--sidebar-text);
  text-decoration: none;
  padding: 0.25rem 0.5rem;
  min-width: 64px;
  border-radius: 8px;
}
.mbnav-link:hover {
  background-color: var(--sidebar-hover);
}
.mbnav-link.router-link-active {
  background-color: var(--sidebar-bg);
  color: var(--sidebar-bg);
}
.mbnav-icon {
  font-size: 22px;
  color: var(--sidebar-text);
}
.mbnav-link.router-link-active .mbnav-icon {
  color: var(--header-active-text);
}
</style>
