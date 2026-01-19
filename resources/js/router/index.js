/*
 * Router Configuration
 * Dynamic nested routes: roles -> courses -> tabs
 */

import { createRouter, createWebHistory } from 'vue-router';

// ==========================
// Role-based courses and tabs
// ==========================
const roleCourses = {
  student: {
    mor: {
      'title-proposals': {
        submission: {},     // ehandles student submissions
        feedback: {}        // feedback from adviser/faculty
      },
      'chapters1-3': {
        submission: {},     // student submission
        evaluation: {}      // evaluation by panels
      }
    },
    dp1: {},               // no tabs for dp1
    dp2: {
      documents: {},        // documents related to dp2
      evaluation: {}        // evaluation tab for dp2
    }
  },

  admin: {
    mor: {
      'title-proposals': {
        groups: {            // group management
          grouplist: {},     // list of groups
          viewSubmission: {} // submission view per group
        },
        enrollees: {}        // enrollees management
      },
      'chapters1-3': {
        submission: {},      // submission tab
        evaluation: {}       // evaluation tab
      },
      'panel-status': {},    // panel status tab
      faculty: {}            // faculty tab
    },
    dp1: {},                  // no tabs for dp1
    dp2: {
      documents: {},           // dp2 documents
      evaluation: {}           // dp2 evaluation
    }
  },

  adviser: {
    mor: {
      'title-proposals': {
        submission: {},
        feedback: {}
      },
      'chapters1-3': {
        submission: {},
        evaluation: {}
      },
      'panel-status': {}
    },
    dp2: {
      documents: {},
      evaluation: {}
    }
  },

  faculty: {
    mor: {
      'title-proposals': {
        submission: {},
        feedback: {}
      },
      'panel-status': {},
      faculty: {}
    }
  }
};


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
    path: '/login',
    name: 'login',
    component: () => import('@/views/auth/login_view.vue'),
    meta: { layout: 'blank', title: 'Login', guestOnly: true }
  },
];

// ==========================
// Authenticated Routes
// ==========================
const authRoutes = [
  {
    path: '/home',
    name: 'home',
    component: () => import('@/views/home_view.vue'),
    meta: { layout: 'AppLayoutDefault', title: 'Home', requiresAuth: true }
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('@/views/dashboard_view.vue'),
    meta: { layout: 'AppLayoutDefault', title: 'Dashboard', requiresAuth: true }
  },
  {
    path: '/courses',
    name: 'courses',
    component: () => import('@/views/courses_view.vue'),
    meta: { layout: 'AppLayoutDefault', title: 'Courses', requiresAuth: false }
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
  },
  {
    path: '/notif',
    name: 'notification',
    component: () => import('@/views/notification_view.vue'),
    meta: { layout: 'AppLayoutDefault', title: 'Notification', requiresAuth: true }
  },
];

// ==========================
// Dynamic Role Routes
// ==========================
const roleRoutes = Object.keys(roleCourses).map(role => ({
  path: `/${role}/courses`,
  component: () => import('@/components/workspace/RoleWorkspace.vue'), // parent workspace container
  meta: { layout: 'AppLayoutDefault', requiresAuth: true },
  children: Object.keys(roleCourses[role]).map(parentTab => ({
    path: parentTab, // e.g., mor, dp1, dp2
    component: () => import('@/components/workspace/RoleWorkspace.vue'), // acts as container for child tabs
    children: Object.keys(roleCourses[role][parentTab]).length > 0 
      ? Object.keys(roleCourses[role][parentTab]).map(childTab => {
          // Map to correct component path based on role and structure
          let componentPath;
          if (role === 'student') {
            componentPath = `@/components/workspace/student/courses/${childTab}.vue`;
          } else if (role === 'adviser') {
            componentPath = `@/components/workspace/adviser/tab/${childTab}.vue`;
          } else {
            // For admin and faculty, use a generic path (components may need to be created)
            componentPath = `@/components/workspace/${role}/${childTab}.vue`;
          }
          
          return {
            path: childTab, // e.g., title-proposals, chapters1-3
            name: `${role}-${parentTab}-${childTab}`,
            component: () => import(componentPath),
            meta: { title: childTab.replace(/-/g, ' ').toUpperCase() },
          };
        })
      : []
  })),
}));

// ==========================
// Combine all routes
// ==========================
const routes = [
  ...guestRoutes,
  ...authRoutes,
  ...roleRoutes,
];

// ==========================
// Router Setup
// ==========================
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) return savedPosition;
    return { top: 0, behavior: 'smooth' };
  }
});

// ==========================
// Navigation Guards
// ==========================
// Temporarily disabled for development - allows access without login
router.beforeEach((to, from, next) => {
  // Set a mock token to bypass auth if none exists (dev only)
  if (!localStorage.getItem('token')) {
    localStorage.setItem('token', 'dev-token-' + Date.now());
  }
  next();
});

// ORIGINAL AUTH CHECK (commented out for dev):
// router.beforeEach((to, from, next) => {
//   const token = localStorage.getItem('token');
//   const isLogged = !!token;
//
//   if (to.meta.requiresAuth && !isLogged) {
//     next({ name: 'access-portal' });
//   } else if (to.meta.guestOnly && isLogged) {
//     next({ name: 'home' });
//   } else {
//     next();
//   }
// });

// Set document title
router.afterEach((to) => {
  if (to.meta.title) {
    document.title = `${to.meta.title} | PUP System`;
  }
});

export default router;
