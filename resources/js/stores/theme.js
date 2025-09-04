/*
 * System Name: Theming and UI Framework
 * Module Name: Theme Settings
 * Purpose Of this file: 
 * Javascript file that handles the logic of theming
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
// Pinia to remember theme
import { defineStore } from 'pinia';
// Theme options
export const useThemeStore = defineStore('theme', {
  state: () => ({
    currentTheme: 'light',
    availableThemes: [
      { id: 'light', name: 'Light' },
      { id: 'dark', name: 'Dark' },
      { id: 'blue', name: 'Blue' },
      { id: 'green', name: 'Green' },
      { id: 'midnight', name: 'Midnight' },
      { id: 'dost', name: 'DOST' },
    ],
  }),

  actions: {
    initTheme() {
      // Load saved theme from localStorage or use system preference
      const savedTheme = localStorage.getItem('theme');
      
      if (savedTheme) {
        this.setTheme(savedTheme);
      } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        this.setTheme('dark');
      } else {
        this.setTheme('light');
      }

      // Watch for system theme changes
      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (!localStorage.getItem('theme')) {
          this.setTheme(e.matches ? 'dark' : 'light');
        }
      });
    },

    setTheme(themeId) {
      const theme = this.availableThemes.find(t => t.id === themeId);
      if (!theme || this.currentTheme === themeId) return;
      
      // Enable smooth color transitions BEFORE variables change
      try {
        const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (!prefersReduced) {
          const root = document.documentElement;
          root.classList.add('theme-transition');
          // Remove any pending timer and schedule cleanup
          window.clearTimeout(window.__themeTransitionTimer);
          window.__themeTransitionTimer = window.setTimeout(() => {
            root.classList.remove('theme-transition');
          }, 400);

          // Sidebar crossfade overlay using previous background color
          const sidebars = document.querySelectorAll('.sidebar');
          sidebars.forEach((sb) => {
            try {
              const cs = getComputedStyle(sb);
              const prevBg = cs.backgroundColor || 'transparent';
              sb.style.setProperty('--sidebar-prev-bg', prevBg);
              sb.classList.add('sidebar-crossfade');
              window.clearTimeout(sb.__sidebarCrossfadeTimer);
              sb.__sidebarCrossfadeTimer = window.setTimeout(() => {
                sb.classList.remove('sidebar-crossfade');
                sb.style.removeProperty('--sidebar-prev-bg');
                sb.__sidebarCrossfadeTimer = undefined;
              }, 420);
            } catch (_) { /* ignore */ }
          });
        }
      } catch (_) {
        // non-fatal
      }

      // Update the theme in the store
      this.currentTheme = theme.id;

      // Update the DOM
      document.documentElement.setAttribute('data-theme', theme.id);
      
      // Save to localStorage
      localStorage.setItem('theme', theme.id);
      
      // Force a reflow to ensure the theme is applied before dispatching the event
      void document.documentElement.offsetHeight;
      
      // Dispatch a custom event when theme changes
      const event = new CustomEvent('theme-changed', { 
        detail: { 
          theme: theme.id,
          timestamp: Date.now() 
        } 
      });
      window.dispatchEvent(event);
      
      // Also trigger a change event on the store for Vue's reactivity
      this.$patch({ currentTheme: theme.id });
    },

    toggleTheme() {
      const currentIndex = this.availableThemes.findIndex(t => t.id === this.currentTheme);
      const nextIndex = (currentIndex + 1) % this.availableThemes.length;
      this.setTheme(this.availableThemes[nextIndex].id);
    },
  },
});
