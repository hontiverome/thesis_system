/*
 * System Name: Theming and UI Framework
 * Module Name: App
 * Purpose Of this file: 
 * To render the main Vue app component
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
*/
import { createApp, h } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import router from './router';
import { useAuth } from './composables/useAuth';
import { useThemeStore } from '@/stores/theme.js';
import '../css/app.css';
import { Icon as IconifyIcon } from '@iconify/vue';
import clickOutside from './directives/click_outside';
import { vPermission } from './directives/vPermission';

const app = createApp({
    setup() {
        // Get auth instance
        const auth = useAuth();
        
        // Initialize auth when the app starts
        auth.initAuth().then(() => {
            console.log('Auth state initialized');
        }).catch(err => {
            console.error('Failed to initialize auth state:', err);
        });
        
        // Return the auth instance to make it available in the template
        return { auth };
    },
    render: () => h(App)
});
// Register the click-outside directive globally
app.directive('click-outside', clickOutside);
app.directive('permission', vPermission);

// Use Pinia for state management
const pinia = createPinia();
app.use(pinia);

// Register Iconify component globally with a unique name
app.component('IconifyIcon', IconifyIcon);

// Initialize theme
const themeStore = useThemeStore();
themeStore.initTheme();

// Smoothly transition from current theme colors to next theme colors
window.addEventListener('theme-changed', () => {
  try {
    // Respect reduced motion preference
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      return;
    }

    const root = document.documentElement; // <html>
    // Toggle a class that enables CSS transitions on color props
    root.classList.add('theme-transition');

    // Remove the class after transitions complete
    window.clearTimeout(window.__themeTransitionTimer);
    window.__themeTransitionTimer = window.setTimeout(() => {
      root.classList.remove('theme-transition');
    }, 400);
  } catch (e) {
    // Non-fatal: ignore animation errors
    console.debug('Theme color transition skipped:', e);
  }
});

// Use router
app.use(router);

app.mount('#app');