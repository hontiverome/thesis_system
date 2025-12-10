/*
 * System Name: Theming and UI Framework
 * Module Name: Auth Configuration
 * Purpose Of this file: 
 * Defines the application's auth routes and their corresponding components.
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


const authRoutes = [
  // 1. Main Landing Page 
  {
    path: '/',
    name: 'landing',
    component: () => import('@/views/landing_view.vue'),
    meta: { 
      layout: 'blank',
      guestOnly: true,
      title: 'Welcome'
    }
  },

  // 2. Access Portal
  {
    path: '/portal',
    name: 'access-portal',
    component: () => import('@/views/auth/access_portal_view.vue'),
    meta: { 
      layout: 'blank',
      guestOnly: true,
      title: 'Select Access Level'
    }
  },

  // 3. Student Login Page
  {
    path: '/login/student',
    name: 'login.student',
    component: () => import('@/views/auth/student_login_view.vue'),
    meta: { 
      layout: 'blank',
      guestOnly: true,
      title: 'Student Login'
    }
  },

  // 4. Faculty Login Page
  {
    path: '/login/faculty',
    name: 'login.faculty',
    component: () => import('@/views/auth/faculty_login_view.vue'),
    meta: { 
      layout: 'blank',
      guestOnly: true,
      title: 'Faculty Login'
    }
  },

  // 5. Admin/Staff Login (original login)
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/auth/login_view.vue'),
    meta: { 
      title: 'Login',
      guestOnly: true // Only accessible when not logged in
    }
  },
    // 6. Register
  {
    path: '/register',
    name: 'register',
    component: () => import('@/views/auth/register_view.vue'),
    meta: { 
      title: 'Register',
      guestOnly: true // Only accessible when not logged in
    }
  },
];

export default authRoutes;