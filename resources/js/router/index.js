import { createRouter, createWebHistory } from 'vue-router';

// Import route modules
import authRoutes from './routes/auth';
import mainRoutes from './routes/main';
import dashboardRoutes from './routes/dashboard';

// Import route guards
import { setupRouteGuards } from './routeGuards';

// --- IMPORT YOUR TEST PAGE ---
import TestComponents from '../Pages/TestComponents.vue'; 

// Combine all routes
const routes = [
  ...authRoutes,      // Authentication related routes
  ...mainRoutes,      // Main application routes
  ...dashboardRoutes, // Dashboard and related routes

  // --- YOUR TEST ROUTE IS HERE ---
  {
    path: '/test-components',
    name: 'TestComponents',
    component: TestComponents,
    meta: {
      requiresAuth: false,
      title: 'UI Test'
    }
  }
];

// Create router instance
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    }
    if (to.hash) {
      return { 
        el: to.hash,
        behavior: 'smooth',
        top: 100 
      };
    }
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