<!---
 * System Name: Theming and UI Framework
 * Module Name: Home
 * Purpose Of this file: 
 * To display the main home view with an overview of the application.
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
  <!-- 
    Home View Component
    Serves as the landing page and introduction to the application
    - Welcome message and brief description
    - Displays current theme information
    - Provides guidance on theme switching
  -->
  <div class="home page-view" role="main" aria-labelledby="page-title">
    <!-- Main heading with welcome message -->
    <h1 id="page-title" class="page-title">Welcome to Theming App</h1>
    
    <!-- Brief introduction -->
    <p class="lead page-subtitle">
      This is the home view. Use the sidebar to navigate between different sections of the application.
    </p>
    
    <!-- Theme information card -->
    <div class="card theme-card">
      <h2>Current Theme: {{ currentThemeName }}</h2>
      <p>
        Try changing the theme using the theme selector in the bottom-left or in the settings page.
        The application will remember your preference across page reloads.
      </p>
    </div>
  </div>
</template>

<script setup>
/**
 * Imports Vue's composition API functions and required dependencies
 */
import { computed } from 'vue';
import { useThemeStore } from '@/stores/theme.js';

/**
 * Component Setup
 * Initializes the theme store and computes the current theme name
 */

// Initialize the theme store to access theme-related state and actions
const themeStore = useThemeStore();

const currentThemeName = computed(() => {
  const theme = themeStore.availableThemes.find(t => t.id === themeStore.currentTheme);
  return theme ? theme.name : 'Default';
});
</script>
