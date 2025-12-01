/*
 * System Name: Theming and UI Framework
 * Module Name: Router Configuration
 * Purpose Of this file: 
 * Main router configuration that combines all route modules and sets up navigation guards.
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

import { createRouter, createWebHistory } from 'vue-router';

// Import route modules
import authRoutes from './routes/auth';
import mainRoutes from './routes/main';
import dashboardRoutes from './routes/dashboard';

// Import route guards
import { setupRouteGuards } from './routeGuards';

// Combine all routes
const routes = [
  ...authRoutes,      // Authentication related routes
  ...mainRoutes,      // Main application routes
  ...dashboardRoutes  // Dashboard and related routes
];

// Create router instance
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  // Improved scroll behavior
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    }
    
    // Scroll to top for new routes, but maintain position when using back/forward
    if (to.hash) {
      return { 
        el: to.hash,
        behavior: 'smooth',
        top: 100 // Offset for fixed headers if needed
      };
    }
    
    // Default scroll to top
    return { top: 0, behavior: 'smooth' };
  }
});

// Setup route guards
setupRouteGuards(router);

// Set page title
router.afterEach((to) => {
  if (to.meta.title) {
    document.title = `${to.meta.title} | Website Template`;
  }
});

export default router;
