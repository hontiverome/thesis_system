/*
 * Router Configuration
 * Fixed: Imports the Adviser Dashboard from the VIEWS folder
 */

import { createRouter, createWebHistory } from 'vue-router';

// 1. IMPORT FROM THE CORRECT LOCATION
// You confirmed the file is in: resources/js/views/adviser/AdviserClassDashboard.vue
import AdviserClassDashboard from '@/views/adviser/AdviserClassDashboard.vue';

// ... other imports ...

// ==========================
// Role-based courses and tabs
// ==========================
const roleCourses = {
  student: {
    mor: { 'title-proposals': {}, 'chapters1-3': {} },
    dp1: {},
    dp2: { documents: {}, evaluation: {} }
  },
  admin: {
    mor: { 'title-proposals': {}, 'chapters1-3': {}, 'panel-status': {}, faculty: {} },
    dp1: {},
    dp2: { documents: {}, evaluation: {} }
  },
  adviser: {
    mor: { 'title-proposals': {}, 'chapters1-3': {}, 'panel-status': {} },
    dp2: { documents: {}, evaluation: {} }
  },
  faculty: {
    mor: { 'title-proposals': {}, 'panel-status': {}, faculty: {} }
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
    meta: { layout: 'AppLayoutDefault', title: 'Home', requiresAuth: true, hideSidebar: true }
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
    meta: { layout: 'AppLayoutDefault', title: 'Courses', requiresAuth: false, hideSidebar: true }
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

  // -----------------------------------------------------------------------
  // [CRITICAL FIX] SPECIFIC ADVISER ROUTE - NOW POINTS TO CORRECT VIEW FILE
  // -----------------------------------------------------------------------
  {
    path: '/adviser/course/:courseCode',
    name: 'AdviserClassDashboard',
    component: AdviserClassDashboard,
    props: true, 
    meta: { layout: 'AppLayoutDefault', requiresAuth: true, title: 'Class Dashboard' }
  },

  // Generic Role Home
  {
    path: '/:role/home',
    name: 'role-home',
    component: () => import('@/components/workspace/RoleHomeOverview.vue'),
    meta: { layout: 'AppLayoutDefault', requiresAuth: true, hideSidebar: true }
  },
  
  // Generic Course Detail (Fallback for Student/Faculty)
  {
    path: '/:role/course/:course',
    name: 'course-detail',
    component: () => import('@/components/workspace/CourseDetail.vue'),
    meta: { layout: 'AppLayoutDefault', requiresAuth: true }
  },

  // Faculty Sections Routes
  {
    path: '/faculty/course/:course/sections',
    name: 'faculty-sections',
    component: () => import('@/components/workspace/FacultySectionsOverview.vue'),
    meta: { layout: 'AppLayoutDefault', requiresAuth: true }
  },
  {
    path: '/faculty/course/:course/section/:section',
    name: 'faculty-section-detail',
    component: () => import('@/components/workspace/CourseDetail.vue'),
    meta: { layout: 'AppLayoutDefault', requiresAuth: true }
  },
];

// ==========================
// Dynamic Role Routes (Nested Tabs)
// ==========================
const roleRoutes = Object.keys(roleCourses).map(role => {
  const courses = roleCourses[role];
  const dynamicChildren = [];

  Object.keys(courses).forEach(courseKey => {
    const tabs = courses[courseKey];
    const hasTabs = Object.keys(tabs).length > 0;

    if (hasTabs) {
      const tabRoutes = Object.keys(tabs).map(tabKey => {
        let componentLoader;
        if (role === 'student') componentLoader = () => import(`@/components/workspace/student/courses/${tabKey}.vue`);
        else if (role === 'adviser') componentLoader = () => import(`@/components/workspace/adviser/tab/${tabKey}.vue`);
        else componentLoader = () => import(`@/components/workspace/${role}/${tabKey}.vue`);

        return {
          path: tabKey,
          name: `${role}-${courseKey}-${tabKey}`,
          component: componentLoader,
          meta: { title: tabKey.replace(/-/g, ' ').toUpperCase() }
        };
      });

      dynamicChildren.push({
        path: courseKey, 
        component: () => import('@/components/workspace/RoleWorkspace.vue'),
        children: tabRoutes
      });
    }
  });

  return {
    path: `/${role}/courses`,
    component: () => import('@/components/workspace/RoleCourseOverview.vue'),
    meta: { layout: 'AppLayoutDefault', requiresAuth: true, hideSidebar: true },
    children: dynamicChildren
  };
});

// ==========================
// Combine Routes
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

// Navigation Guards
router.beforeEach((to, from, next) => {
  if (!localStorage.getItem('token')) {
    localStorage.setItem('token', 'dev-token-' + Date.now());
  }
  next();
});

router.afterEach((to) => {
  if (to.meta.title) {
    document.title = `${to.meta.title} | PUP System`;
  }
});

export default router;