/*
 * Router Configuration
 * Updated to support Blank vs. Default layouts explicitly
 */

import { createRouter, createWebHistory } from 'vue-router';

// 1. Define Routes Directly to ensure Meta Tags work
const routes = [
  // --- GUEST ROUTES (Blank Layout) ---
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
    path: '/login/student',
    name: 'login.student',
    component: () => import('@/views/auth/student_login_view.vue'),
    meta: { layout: 'blank', title: 'Student Login', guestOnly: true }
  },
  {
    path: '/login/faculty',
    name: 'login.faculty',
    component: () => import('@/views/auth/faculty_login_view.vue'),
    meta: { layout: 'blank', title: 'Faculty Login', guestOnly: true }
  },
  {
    path: '/register',
    name: 'register',
    component: () => import('@/views/auth/register_view.vue'),
    meta: { layout: 'blank', title: 'Register', guestOnly: true }
  },
  {
    path: '/login', // Fallback for admin/staff
    name: 'login',
    component: () => import('@/views/auth/login_view.vue'),
    meta: { layout: 'blank', title: 'Login', guestOnly: true }
  },

  // --- AUTHENTICATED ROUTES (Default Layout with Sidebar/Navbar) ---
  {
    path: '/home',  // Changed from '/' to '/home'
    name: 'home',
    component: () => import('@/views/home_view.vue'),
    meta: { layout: 'AppLayoutDefault', title: 'Home', requiresAuth: true }
  },
  {
    path: '/courses',
    name: 'courses',
    component: () => import('@/views/courses_view.vue'),
    meta: { layout: 'AppLayoutDefault', title: 'Courses', requiresAuth: true }
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('@/views/dashboard_view.vue'),
    meta: { layout: 'AppLayoutDefault', title: 'Dashboard', requiresAuth: true }
  },
  {
    path: '/profile',
    name: 'profile',
    component: () => import('@/views/profile_view.vue'),
    meta: { layout: 'AppLayoutDefault', title: 'Profile', requiresAuth: true }
  },
  {
    path: '/settings',
    name: 'settings',
    component: () => import('@/views/settings_view.vue'),
    meta: { layout: 'AppLayoutDefault', title: 'Settings', requiresAuth: true }
  },
  {
    path: '/help',
    name: 'help',
    component: () => import('@/views/help_view.vue'),
    meta: { layout: 'AppLayoutDefault', title: 'Help & Support' }
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) return savedPosition;
    return { top: 0, behavior: 'smooth' };
  }
});

// // Basic Navigation Guard (Placeholder - extend as needed)
// router.beforeEach((to, from, next) => {
//   const token = localStorage.getItem('token'); // Adjust based on your Auth logic
//   const isLogged = !!token;

//   if (to.meta.requiresAuth && !isLogged) {
//     next({ name: 'access-portal' });
//   } else if (to.meta.guestOnly && isLogged) {
//     next({ name: 'home' });
//   } else {
//     next();
//   }
// });

// TEMPORARY: Allow access to everything
router.beforeEach((to, from, next) => {
  next(); 
});

// Set page title
router.afterEach((to) => {
  if (to.meta.title) {
    document.title = `${to.meta.title} | PUP System`;
  }
});

export default router;