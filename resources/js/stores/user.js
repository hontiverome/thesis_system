/*
 * System Name: Theming and UI Framework
 * Module Name: User Store
 * Purpose Of this file: 
 * Manages user authentication state and profile data
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
  // Load initial state from storage or default to null
  const user = ref(JSON.parse(localStorage.getItem('userData')) || null);
  const token = ref(localStorage.getItem('auth_token') || null);
  const initialized = ref(false);
  
  // UI States (Avatars, Loading, Errors)
  const avatarPreview = ref(null);
  const isLoading = ref(false);
  const error = ref(null);
  const avatarMaxSizeMB = ref(4);
  const avatarMaxSizeBytes = computed(() => avatarMaxSizeMB.value * 1024 * 1024);

  // --- AUTHORIZATION GETTERS (Step 1 Requirement) ---

  // 1. Get All Roles (Safely sanitizes to lowercase strings)
  const roles = computed(() => {
    if (!user.value?.roles) return [];
    
    return user.value.roles.map(r => {
        // Handle simple string array
        if (typeof r === 'string') return r.toLowerCase();
        // Handle object array (checks for RoleName or name)
        const name = r?.RoleName || r?.name || '';
        return name.toLowerCase();
    }).filter(r => r !== ''); // Remove empty strings
  });

  // 2. Get Permissions (Safely sanitizes input)
  const getPermissions = computed(() => {
    if (!user.value?.permissions) return [];
    
    return user.value.permissions.map(p => {
        // Handle simple string array
        if (typeof p === 'string') return p.toLowerCase();
        // Handle object array (safely access name)
        const name = p?.name || p?.permissionName || ''; 
        return name.toLowerCase();
    }).filter(p => p !== ''); // Remove empty strings
  });

  // 3. hasRole (Step 1 Requirement)
  const hasRole = (roleName) => {
      return roles.value.includes(roleName.toLowerCase());
  };

  // 4. Role Booleans (Mapping the 4 key roles)
  const isStudent = computed(() => hasRole('student'));
  const isFaculty = computed(() => hasRole('faculty'));
  const isAdviser = computed(() => hasRole('adviser'));
  const isResearchCoordinator = computed(() => hasRole('research coordinator') || hasRole('research_coordinator'));
  
  // 5. isAdmin (Step 1 Requirement)
  // We treat Admin and Research Coordinator as high-privilege users
  const isAdmin = computed(() => hasRole('admin') || hasRole('administrator') || isResearchCoordinator.value);

  // --- UI GETTERS ---
  const isAuthenticated = computed(() => !!token.value && !!user.value);
  
  const userInitials = computed(() => {
    const f = user.value?.firstName || user.value?.first_name || '';
    const l = user.value?.lastName || user.value?.last_name || '';
    return `${f[0] || ''}${l[0] || ''}`.toUpperCase() || 'U';
  });

  const memberSinceFormatted = computed(() => {
    if (!user.value?.memberSince && !user.value?.created_at) return 'Member for a while';
    const dateStr = user.value.memberSince || user.value.created_at;
    const joinDate = new Date(dateStr);
    return `Member since ${joinDate.toLocaleString('default', { month: 'long' })} ${joinDate.getFullYear()}`;
  });

  const lastLoginFormatted = computed(() => {
    const dateStr = user.value?.lastLogin || user.value?.updated_at;
    if (!dateStr) return 'Recently';
    return `Last seen ${formatDate(dateStr)}`;
  });

  const statusOptions = [
    { value: 'active', label: 'Active' },
    { value: 'away', label: 'Away' },
    { value: 'busy', label: 'Busy' },
    { value: 'offline', label: 'Offline' }
  ];

  // --- ACTIONS (State Management) ---

  function setUser(userData) {
    user.value = userData;
    localStorage.setItem('userData', JSON.stringify(userData));
    initialized.value = true;
  }

  function setToken(newToken) {
    token.value = newToken;
    localStorage.setItem('auth_token', newToken);
  }

  function clearUser() {
    user.value = null;
    token.value = null;
    localStorage.removeItem('auth_token');
    localStorage.removeItem('userData');
    localStorage.removeItem('user_avatar'); // Clear legacy avatar cache
    initialized.value = false;
  }

  // --- ACTIONS (UI/Profile - Preserved from your original file) ---

  async function updateAvatar(file) {
    return new Promise((resolve, reject) => {
      error.value = null;
      if (!file) {
        user.value.avatar = null;
        setUser(user.value);
        return resolve(null);
      }
      if (!file.type.startsWith('image/')) return reject(new Error('Image only'));
      if (file.size > avatarMaxSizeBytes.value) return reject(new Error('File too large'));

      const reader = new FileReader();
      reader.onload = (e) => {
        // Update local state with base64 preview
        const updatedUser = { ...user.value, avatar: e.target.result };
        setUser(updatedUser);
        resolve({ success: true, avatar: updatedUser.avatar });
      };
      reader.readAsDataURL(file);
    });
  }

  async function removeAvatar() {
      const updatedUser = { ...user.value, avatar: null };
      setUser(updatedUser);
      return { success: true };
  }

  async function updateProfile(profileData) {
      isLoading.value = true;
      try {
        // Simulate API delay
        await new Promise(resolve => setTimeout(resolve, 500));
        const updatedUser = {
            ...user.value,
            firstName: profileData.firstName || user.value.firstName,
            lastName: profileData.lastName || user.value.lastName,
            email: profileData.email || user.value.email,
            updatedAt: new Date().toISOString()
        };
        setUser(updatedUser);
        return { success: true, data: user.value };
      } catch (err) {
        error.value = err.message;
        return { success: false, error: err.message };
      } finally {
        isLoading.value = false;
      }
  }

  // Permission Helper
  // Returns true if user has specific permission OR is an Admin
  const can = (permission) => {
      if (!permission) return false;
      return getPermissions.value.includes(permission.toLowerCase()) || isAdmin.value;
  };

  return {
    // State
    user, token, initialized, isAuthenticated, isLoading, error, avatarMaxSizeMB,
    
    // Auth Getters
    roles, getPermissions, hasRole,
    isStudent, isFaculty, isAdviser, isResearchCoordinator, isAdmin,
    
    // UI Getters
    userInitials, memberSinceFormatted, lastLoginFormatted, statusOptions,
    
    // Actions
    setUser, setToken, clearUser, can,
    updateProfile, updateAvatar, removeAvatar
  };
});