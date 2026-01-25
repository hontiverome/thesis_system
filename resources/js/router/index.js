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
]

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
        path: '',
        name: `${role}-workspace-root`,
        component: () => import('@/views/workspace/shared/OverviewTemplate.vue'),
        meta: { title: 'Overview' }
      },
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
        path: 'title-proposals',
        name: `${role}-title-proposals`,
        component: () => {
          // Use the 'role' from the map loop scope directly for stability
          const roleToProposalComponentMap = {
            student: () => import("@/views/workspace/student/StudProposals.vue"),
            faculty: () => import("@/views/workspace/shared/ProposalsTemplate.vue"),
            adviser: () => import("@/views/workspace/shared/ProposalsTemplate.vue"),
            admin: () => import("@/views/workspace/shared/ProposalsTemplate.vue")
          };
          const loader = roleToProposalComponentMap[role] || (() => import("@/views/workspace/shared/OverviewTemplate.vue"));
          return loader(); // Execute the loader to return the Promise
        },
        meta: { title: 'Title Proposals' }
      },
      {
        path: 'chapters-1-3',
        name: `${role}-chapters-1-3`,
        component: () => {
          const roleToChapterComponentMap = {
            student: () => import("@/views/workspace/student/StudChapters.vue"),
            faculty: () => import("@/views/workspace/shared/ChaptersTemplate.vue"),
            adviser: () => import("@/views/workspace/shared/ChaptersTemplate.vue"),
            admin: () => import("@/views/workspace/shared/ChaptersTemplate.vue")
          };
          const loader = roleToChapterComponentMap[role] || (() => import("@/views/workspace/shared/OverviewTemplate.vue"));
          return loader();
        },
        meta: { title: 'Chapters 1-3' }
      },
      {
        path: 'panel-status',
        name: `${role}-panel-status`,
        component: () => {
          const roleToPanelComponent = {
            student: () => import("@/views/workspace/student/StudPanel.vue"),
            faculty: () => import("@/views/workspace/shared/PanelTemplate.vue"),
            adviser: () => import("@/views/workspace/shared/PanelTemplate.vue"),
            admin: () => import("@/views/workspace/shared/PanelTemplate.vue")
          };
          const loader = roleToPanelComponent[role] || (() => import("@/views/workspace/shared/OverviewTemplate.vue"));
          return loader();
        },
        meta: { title: 'Panel Status' }
      },
      {
        path: 'faculty',
        name: `${role}-faculty`,
        component: () => {
          const roleToPanelComponent = {
            faculty: () => import("@/views/workspace/shared/FacultyTemplate.vue"),
            adviser: () => import("@/views/workspace/shared/FacultyTemplate.vue"),
            admin: () => import("@/views/workspace/shared/FacultyTemplate.vue")
          };
          const loader = roleToPanelComponent[role] || (() => import("@/views/workspace/shared/OverviewTemplate.vue"));
          return loader();
        },
        meta: { title: 'Faculty' }
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
  // 1. Prioritize the meta title defined in roleRoutes
  // 2. Fallback to the route name 
  // 3. Last resort fallback to 'System'
  let pageTitle = to.meta.title;
  if (!pageTitle && to.name) {
    pageTitle = String(to.name)
      .split('-')
      .slice(1) // Remove the role prefix
      .map(word => word.charAt(0).toUpperCase() + word.slice(1))
      .join(' ');
  }
  document.title = `${pageTitle || 'Dashboard'} | PUP T-SIS`;
});

export default router;