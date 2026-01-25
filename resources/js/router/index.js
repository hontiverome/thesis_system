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
    path: '/portal',
    name: 'access-portal',
    component: () => import('@/views/auth/access_portal_view.vue'),
    meta: { layout: 'blank', title: 'Select Access Level', guestOnly: true }
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
    component: () => import('@/views/workspace/RoleHomeOverview.vue'),
    meta: { layout: 'AppLayoutDefault', requiresAuth: true, hideSidebar: true }
  },
  {
    path: '/:role/courses',
    name: 'role-courses-overview',
    component: () => import('@/views/workspace/RoleCourseOverview.vue'),
    meta: { layout: 'AppLayoutDefault', requiresAuth: true, hideSidebar: true }
  }
];

// ==========================
// Dynamic Workspace Routes
// ==========================

const roleRoutes = Object.keys(ROLE_METADATA).map(role => {
  return {
    path: '/:role/course/:course',
    component: () => import('@/views/workspace/RoleWorkspace.vue'),
    meta: { requiresAuth: true, hideSidebar: false },
    children: [
      {
        path: 'overview',
        name: `${role}-overview`,
        component: () => import('@/views/workspace/shared/OverviewTemplate.vue'),
        meta: { title: 'Overview' }
      },
      {
        path: 'sections',
        name: `${role}-sections-overview`,
        component: () => import('@/views/workspace/shared/SectionsOverview.vue'),
        meta: { title: 'Sections' }
      },
      {
        path: 'sections/:section',
        name: `${role}-section-detail`,
        component: () => import('@/views/workspace/shared/SectionsDetail.vue'),
        props: true,
        meta: { title: 'Section Detail' }
      },
      {
        path: 'title-proposals',
        name: `${role}-title-proposals`,
        component: () => import('@/views/workspace/student/StudProposals.vue'),
        meta: { title: 'Title Proposals' }
      },
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