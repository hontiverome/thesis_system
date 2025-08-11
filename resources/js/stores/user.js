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

import { defineStore } from 'pinia';
import { ref, computed } from 'vue';

export const useUserStore = defineStore('user', () => {
  // State
  const user = ref({
    id: 'user-123',
    name: 'John Doe',
    email: 'john.doe@example.com',
    status: 'Active',
    avatar: null,
    memberSince: new Date('2025-01-15').toISOString(),
    lastLogin: new Date().toISOString(),
  });
  
  const isAuthenticated = ref(true); // For future auth implementation
  const authToken = ref(localStorage.getItem('authToken') || null);
  const avatarPreview = ref(null);

  // Getters
  const userInitials = computed(() => {
    if (!user.value.name) return 'U';
    return user.value.name
      .split(' ')
      .map(word => word[0])
      .join('')
      .toUpperCase()
      .substring(0, 2);
  });

  const memberSinceFormatted = computed(() => {
    if (!user.value.memberSince) return 'Member for a while';
    const joinDate = new Date(user.value.memberSince);
    return `Member since ${joinDate.toLocaleString('default', { month: 'long' })} ${joinDate.getFullYear()}`;
  });

  // Actions
  function updateProfile(profileData) {
    user.value = { ...user.value, ...profileData };
    // In a real app, this would be an API call
    return Promise.resolve(user.value);
  }

  function updateAvatar(file) {
    return new Promise((resolve) => {
      if (!file) {
        user.value.avatar = null;
        avatarPreview.value = null;
        return resolve(null);
      }

      // Create preview
      const reader = new FileReader();
      reader.onload = (e) => {
        avatarPreview.value = e.target.result;
        // In a real app, upload the file and get the URL
        // For now, data URL will be used
        user.value.avatar = e.target.result;
        resolve(e.target.result);
      };
      reader.readAsDataURL(file);
    });
  }

  // Mock authentication methods (for future implementation - API CALLS)
  async function login(credentials) {
    return new Promise((resolve) => {
      setTimeout(() => {
        authToken.value = 'mock-jwt-token';
        localStorage.setItem('authToken', authToken.value);
        isAuthenticated.value = true;
        resolve({ success: true });
      }, 500);
    });
  }

  async function register(userData) {
    return new Promise((resolve) => {
      setTimeout(() => {
        Object.assign(user.value, userData);
        authToken.value = 'mock-jwt-token';
        localStorage.setItem('authToken', authToken.value);
        isAuthenticated.value = true;
        resolve({ success: true });
      }, 500);
    });
  }

  function logout() {
    authToken.value = null;
    localStorage.removeItem('authToken');
    isAuthenticated.value = false;
    // Reset user data or redirect to login
  }

  // Initialize from localStorage if needed
  function initialize() {
    // Token validation
    if (authToken.value) {
      isAuthenticated.value = true;
    }
  }

  // Initialize the store
  initialize();

  return {
    // State
    user,
    isAuthenticated,
    authToken,
    avatarPreview,
    
    // Getters
    userInitials,
    memberSinceFormatted,
    
    // Actions
    updateProfile,
    updateAvatar,
    login,
    register,
    logout,
  };
});
