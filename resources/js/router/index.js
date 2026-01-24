/*
 * Router Configuration - Scalable Dynamic Version
 */

import { createRouter, createWebHistory } from 'vue-router';
import { ROLE_METADATA } from '@/config/roleConfig';

// ==========================
// Guest Routes
// ==========================
const guestRoutes = [
  {
    path: '/',
    name: 'landing',
    component: () => import('@/views/landing_view.vue'),
    meta: { layout: 'blank', title: 'Welcome', guestOnly: true }
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/auth/login_view.vue'),
    meta: { layout: 'blank', title: 'Login', guestOnly: true }
  },
  // ... (Add other guest routes like /portal or /register here)
];

// ==========================
// Authenticated Static Routes
// ==========================
const authRoutes = [
  {
    path: '/home',
    name: 'home',
    component: () => import('@/views/home_view.vue'),
    meta: { layout: 'AppLayoutDefault', title: 'Home', requiresAuth: true, hideSidebar: true }
  },
  {
    path: '/:role/home',
    name: 'role-home',
    component: () => import('@/components/workspace/RoleHomeOverview.vue'),
    meta: { layout: 'AppLayoutDefault', requiresAuth: true, hideSidebar: true }
  },
  {
    path: '/:role/courses',
    name: 'role-courses-overview',
    component: () => import('@/components/workspace/RoleCourseOverview.vue'),
    meta: { layout: 'AppLayoutDefault', requiresAuth: true, hideSidebar: true }
  }
];

// ==========================
// Dynamic Workspace Routes (The Scalable Part)
// ==========================

const roleRoutes = Object.keys(ROLE_METADATA).map(role => {
  return {
    // We use :role so the router recognizes 'role' as a valid parameter
    path: `/:role/course/:course`, 
    component: () => import('@/components/workspace/RoleWorkspace.vue'),
    meta: { requiresAuth: true },
    children: [
      {
        path: ':tab', 
        // This generates 'student-workspace-tab', 'adviser-workspace-tab', etc.
        name: `${role}-workspace-tab`, 
        component: () => import('@/components/workspace/TabHandler.vue'),
        meta: { requiresAuth: true }
      },
      {
        path: '', 
        redirect: to => ({ path: `${to.path}/overview` })
      }
    ]
  };
});

// ==========================
// Combine and Setup
// ==========================
const routes = [
  ...guestRoutes,
  ...authRoutes,
  ...roleRoutes,
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) return savedPosition;
    return { top: 0, behavior: 'smooth' };
  }
});

router.beforeEach((to, from, next) => {
  // Simple dev-token check
  if (!localStorage.getItem('token')) {
    localStorage.setItem('token', 'dev-token-' + Date.now());
  }
  next();
});

router.afterEach((to) => {
  // Set the browser tab title dynamically from the route meta or tab param
  const pageTitle = to.params.tab 
    ? to.params.tab.replace(/-/g, ' ').toUpperCase() 
    : (to.meta.title || 'System');
    
  document.title = `${pageTitle} | PUP T-SIS`;
});

export default router;