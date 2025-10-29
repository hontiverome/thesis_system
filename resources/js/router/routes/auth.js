const authRoutes = [
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/auth/login_view.vue'),
    meta: { 
      title: 'Login',
      guestOnly: true // Only accessible when not logged in
    }
  },
  // Add other auth-related routes here if needed
];

export default authRoutes;
