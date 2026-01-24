/*
 * Router Configuration
 */

import { createRouter, createWebHistory } from 'vue-router';

// ==========================
// Role-based courses and tabs
// ==========================
const roleCourses = {
  student: {
    mor: { 'overview': {}, 'title-proposals': {}, 'chapters1-3': {}, 'panel-status': {} },
    dp1: { 'overview': {}, 'revised-chapters1-3': {}, 'panel-status': {} },
    dp2: { 'overview': {}, 'research-thesis': {}, 'evaluation': {} }
  },
  admin: {
    mor: { 'overview': {}, 'settings': {}, 'logs': {} },
    dp1: { 'overview': {} },
    dp2: { 'overview': {}, 'documents': {}, 'evaluation': {} }
  },
  adviser: {
    mor: { 'overview': {}, 'advisees': {}, 'reviews': {}, 'grades': {} },
    dp2: { 'overview': {}, 'documents': {}, 'evaluation': {} }
  },
  faculty: {
    mor: { 'overview': {}, 'sections': {}, 'materials': {}, 'grading': {} }
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

  {
    path: '/:role/home',
    name: 'role-home',
    component: () => import('@/components/workspace/RoleHomeOverview.vue'),
    meta: { layout: 'AppLayoutDefault', requiresAuth: true, hideSidebar: true }
  },
  
  {
    path: '/:role/course/:course',
    component: () => import('@/components/workspace/CourseDetail.vue'),
    meta: { layout: 'AppLayoutDefault', requiresAuth: true },
    children: [] 
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
    const tabNames = Object.keys(tabs);
    
    const tabRoutes = tabNames.map(tabKey => {
      // Logic to convert kebab-case (title-proposals) to PascalCase (TitleProposals)
      const fileName = tabKey.split('-')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join('');

      return {
        path: tabKey,
        name: `${role}-${courseKey}-${tabKey}`,
        // Imports directly from the flat folder structure
        component: () => import(`@/components/workspace/${role}/${fileName}.vue`),
        meta: { title: tabKey.replace(/-/g, ' ').toUpperCase() }
      };
    });

    dynamicChildren.push({
      path: courseKey, 
      name: `${role}-${courseKey}-base`,
      redirect: tabNames.length > 0 ? { name: `${role}-${courseKey}-${tabNames[0]}` } : undefined,
      component: () => import('@/components/workspace/RoleWorkspace.vue'),
      meta: { layout: 'AppLayoutDefault', requiresAuth: true, hideSidebar: false },
      children: tabRoutes
    });
  });

  return {
    path: `/${role}/courses`,
    name: `${role}-courses-overview`,
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