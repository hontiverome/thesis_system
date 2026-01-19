/*
 * System Name: Theming and UI Framework
 * Module Name: User Store
 * Purpose: Manages user authentication state, authorization logic, and profile data
 */

import { defineStore } from 'pinia';
import { ref, computed } from 'vue';

// Helper function to format dates
const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    year: 'numeric', month: 'long', day: 'numeric',
    hour: '2-digit', minute: '2-digit'
  });
};

export const useUserStore = defineStore('user', () => {
  // --- STATE ---
  
  // Check for existing token in localStorage
  const existingToken = localStorage.getItem('auth_token');
  const user = ref(null);
  const token = ref(existingToken);
  const initialized = ref(false);
  
  // UI States
  const avatarPreview = ref(null);
  const isLoading = ref(false);
  const error = ref(null);
  const avatarMaxSizeMB = ref(4);
  const avatarMaxSizeBytes = computed(() => avatarMaxSizeMB.value * 1024 * 1024);

  // --- AUTHORIZATION GETTERS ---

  const roles = computed(() => {
    if (!user.value?.roles) return [];
    return user.value.roles.map(r => (typeof r === 'string' ? r.toLowerCase() : (r.name || '').toLowerCase()));
  });

  const getPermissions = computed(() => {
    if (!user.value?.permissions) return [];
    return user.value.permissions.map(p => (typeof p === 'string' ? p.toLowerCase() : (p.name || '').toLowerCase()));
  });

  const hasRole = (roleName) => roles.value.includes(roleName.toLowerCase());

  // Role Booleans
  const isStudent = computed(() => hasRole('student'));
  const isFaculty = computed(() => hasRole('faculty'));
  const isAdviser = computed(() => hasRole('adviser'));
  const isResearchCoordinator = computed(() => hasRole('research coordinator'));
  const isAdmin = computed(() => hasRole('admin') || isResearchCoordinator.value);

  // --- UI GETTERS ---
  const isAuthenticated = computed(() => !!token.value && !!user.value);
  
  const userInitials = computed(() => {
    const f = user.value?.firstName || '';
    const l = user.value?.lastName || '';
    return `${f[0] || ''}${l[0] || ''}`.toUpperCase() || 'U';
  });

  const memberSinceFormatted = computed(() => {
    if (!user.value?.memberSince) return 'Member for a while';
    const date = new Date(user.value.memberSince);
    return `Member since ${date.toLocaleString('default', { month: 'long' })} ${date.getFullYear()}`;
  });

  const lastLoginFormatted = computed(() => {
    return `Last seen ${formatDate(user.value?.lastLogin)}`;
  });

  // --- ACTIONS ---

  function setUser(userData) {
    user.value = userData;
  }

  function setToken(newToken) {
    token.value = newToken;
    if (newToken) {
      localStorage.setItem('auth_token', newToken);
    } else {
      localStorage.removeItem('auth_token');
    }
  }

  function clearUser() {
    user.value = null;
    token.value = null;
    localStorage.removeItem('auth_token');
  }

  const can = (permission) => {
      if (!permission) return false;
      return getPermissions.value.includes(permission.toLowerCase()) || isAdmin.value;
  };

  return {
    user, token, initialized, isAuthenticated, isLoading, error,
    roles, getPermissions, hasRole,
    isStudent, isFaculty, isAdviser, isResearchCoordinator, isAdmin,
    userInitials, memberSinceFormatted, lastLoginFormatted,
    setUser, setToken, clearUser, can
  };
});