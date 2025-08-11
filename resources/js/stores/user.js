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
import { ref, computed, onMounted } from 'vue';

// Helper function to format dates
const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

export const useUserStore = defineStore('user', () => {
  // State
  const user = ref({
    id: 'user-123',
    name: 'John Doe',
    email: 'john.doe@example.com',
    status: 'active',
    avatar: null,
    memberSince: new Date('2025-01-15').toISOString(),
    lastLogin: new Date().toISOString(),
  });
  
  const isAuthenticated = ref(true);
  const authToken = ref(localStorage.getItem('authToken') || null);
  const avatarPreview = ref(null);
  const isLoading = ref(false);
  const error = ref(null);

  // Available status options
  const statusOptions = [
    { value: 'active', label: 'Active' },
    { value: 'away', label: 'Away' },
    { value: 'busy', label: 'Busy' },
    { value: 'offline', label: 'Offline' }
  ];

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

  const lastLoginFormatted = computed(() => {
    if (!user.value.lastLogin) return 'Recently';
    return `Last seen ${formatDate(user.value.lastLogin)}`;
  });

  // Actions
  async function updateProfile(profileData) {
    isLoading.value = true;
    error.value = null;
    
    try {
      // Simulate API call
      await new Promise(resolve => setTimeout(resolve, 500));
      
      // Update user data
      user.value = { ...user.value, ...profileData };
      
      // Update last modified timestamp
      user.value.updatedAt = new Date().toISOString();
      
      // In a real app, you would save to the server here
      // await api.updateUserProfile(user.value.id, profileData);
      
      return { success: true, data: user.value };
    } catch (err) {
      console.error('Failed to update profile:', err);
      error.value = err.message || 'Failed to update profile';
      return { success: false, error: error.value };
    } finally {
      isLoading.value = false;
    }
  }

  async function updateAvatar(file) {
    return new Promise((resolve, reject) => {
      if (!file) {
        // If no file, remove the avatar
        user.value.avatar = null;
        avatarPreview.value = null;
        localStorage.removeItem('user_avatar');
        return resolve(null);
      }

      // Validate file type
      if (!file.type.startsWith('image/')) {
        const err = new Error('Please select an image file');
        error.value = err.message;
        return reject(err);
      }

      // Validate file size (max 2MB)
      if (file.size > 2 * 1024 * 1024) {
        const err = new Error('Image size should be less than 2MB');
        error.value = err.message;
        return reject(err);
      }

      const reader = new FileReader();
      
      reader.onload = (e) => {
        try {
          // Store the data URL for preview
          avatarPreview.value = e.target.result;
          
          // In a real app, you would upload the file to a server here
          // and get back a URL to store in the user's profile
          // For now, we'll store the data URL directly
          user.value.avatar = e.target.result;
          
          // Persist to localStorage (temporary solution)
          localStorage.setItem('user_avatar', e.target.result);
          
          resolve(e.target.result);
        } catch (err) {
          console.error('Error processing image:', err);
          error.value = 'Failed to process image';
          reject(err);
        }
      };
      
      reader.onerror = (error) => {
        console.error('Error reading file:', error);
        const err = new Error('Failed to read the file');
        error.value = err.message;
        reject(err);
      };
      
      reader.readAsDataURL(file);
    });
  }

  // Mock authentication methods (for future implementation)
  async function login(credentials) {
    isLoading.value = true;
    error.value = null;
    
    try {
      // Simulate API call
      await new Promise(resolve => setTimeout(resolve, 800));
      
      // In a real app, this would be an API call to your backend
      // const response = await api.login(credentials);
      
      // Mock response
      authToken.value = 'mock-jwt-token';
      localStorage.setItem('authToken', authToken.value);
      isAuthenticated.value = true;
      
      // Update last login time
      user.value.lastLogin = new Date().toISOString();
      
      return { success: true };
    } catch (err) {
      console.error('Login failed:', err);
      error.value = err.message || 'Login failed. Please check your credentials.';
      return { success: false, error: error.value };
    } finally {
      isLoading.value = false;
    }
  }

  async function register(userData) {
    isLoading.value = true;
    error.value = null;
    
    try {
      // Simulate API call
      await new Promise(resolve => setTimeout(resolve, 1000));
      
      // In a real app, this would be an API call to your backend
      // const response = await api.register(userData);
      
      // Mock response
      Object.assign(user.value, userData, {
        id: `user-${Math.random().toString(36).substr(2, 9)}`,
        status: 'active',
        memberSince: new Date().toISOString(),
        lastLogin: new Date().toISOString()
      });
      
      authToken.value = 'mock-jwt-token';
      localStorage.setItem('authToken', authToken.value);
      isAuthenticated.value = true;
      
      return { success: true };
    } catch (err) {
      console.error('Registration failed:', err);
      error.value = err.message || 'Registration failed. Please try again.';
      return { success: false, error: error.value };
    } finally {
      isLoading.value = false;
    }
  }

  function logout() {
    // In a real app, you might want to call an API to invalidate the token
    // await api.logout();
    
    authToken.value = null;
    localStorage.removeItem('authToken');
    isAuthenticated.value = false;
    
    // Reset user data (except for the avatar preview)
    const currentAvatar = user.value.avatar;
    user.value = {
      id: '',
      name: '',
      email: '',
      status: 'offline',
      avatar: currentAvatar, // Keep the avatar for the login screen
      memberSince: '',
      lastLogin: ''
    };
  }

  // Initialize the store
  function initialize() {
    try {
      // In a real app, you would validate the token here
      if (authToken.value) {
        // Check if token is expired, etc.
        isAuthenticated.value = true;
        
        // Load user data from localStorage (temporary solution)
        const savedAvatar = localStorage.getItem('user_avatar');
        if (savedAvatar) {
          user.value.avatar = savedAvatar;
          avatarPreview.value = savedAvatar;
        }
      }
    } catch (err) {
      console.error('Failed to initialize user store:', err);
      // Clear invalid data
      localStorage.removeItem('authToken');
      localStorage.removeItem('user_avatar');
      isAuthenticated.value = false;
    }
  }

  // Initialize the store when created
  onMounted(() => {
    initialize();
  });

  return {
    // State
    user,
    isAuthenticated,
    authToken,
    avatarPreview,
    isLoading,
    error,
    
    // Getters
    userInitials,
    memberSinceFormatted,
    lastLoginFormatted,
    statusOptions,
    
    // Actions
    updateProfile,
    updateAvatar,
    login,
    register,
    logout,
    initialize,
  };
});
