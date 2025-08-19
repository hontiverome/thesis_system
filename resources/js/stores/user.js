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

// Guest/default user factory
const getGuestUser = () => ({
  id: 'guest',
  firstName: 'Guest',
  lastName: '',
  email: 'user@example.com',
  status: 'offline',
  avatar: null,
  memberSince: '',
  lastLogin: ''
});

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
  // State - Load from localStorage, sessionStorage, or use defaults
  const loadUserFromStorage = () => {
    // First try localStorage
    let savedUser = localStorage.getItem('userData');
    
    // If not in localStorage, try sessionStorage
    if (!savedUser) {
      savedUser = sessionStorage.getItem('userData');
    }
    
    if (savedUser) {
      try {
        return JSON.parse(savedUser);
      } catch (e) {
        console.error('Failed to parse saved user data', e);
      }
    }
    
    // Default to guest user when nothing is saved
    return getGuestUser();
  };

  const user = ref(loadUserFromStorage());
  
  // Helper function to estimate the size of an object in bytes
  const getObjectSize = (obj) => {
    return new Blob([JSON.stringify(obj)]).size;
  };

  // Save user data to storage with fallback to sessionStorage if too large
  const saveUserToStorage = () => {
    const userData = user.value;
    const dataSize = getObjectSize(userData);
    
    // If data is larger than 4.5MB (safely under 5MB localStorage limit)
    if (dataSize > 4.5 * 1024 * 1024) {
      console.warn('User data too large for localStorage, using sessionStorage instead');
      sessionStorage.setItem('userData', JSON.stringify(userData));
      localStorage.removeItem('userData');
    } else {
      try {
        localStorage.setItem('userData', JSON.stringify(userData));
        // Clean up any old sessionStorage data if it exists
        sessionStorage.removeItem('userData');
      } catch (e) {
        console.error('Failed to save to localStorage, falling back to sessionStorage', e);
        sessionStorage.setItem('userData', JSON.stringify(userData));
      }
    }
  };
  
  const authToken = ref(localStorage.getItem('authToken') || null);
  const isAuthenticated = ref(!!authToken.value);
  const avatarPreview = ref(null);
  const isLoading = ref(false);
  const error = ref(null);
  // Set max avatar size to 4MB to leave room for other user data
  const avatarMaxSizeMB = ref(4);
  const avatarMaxSizeBytes = computed(() => avatarMaxSizeMB.value * 1024 * 1024);

  // Available status options
  const statusOptions = [
    { value: 'active', label: 'Active' },
    { value: 'away', label: 'Away' },
    { value: 'busy', label: 'Busy' },
    { value: 'offline', label: 'Offline' }
  ];

  // Computed
  const userInitials = computed(() => {
    const firstName = user.value?.firstName || '';
    const lastName = user.value?.lastName || '';
    
    if (!firstName && !lastName) return 'U';
    
    return `${firstName[0] || ''}${lastName[0] || ''}`.toUpperCase();
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
      user.value = {
        ...user.value,
        firstName: profileData.firstName || user.value.firstName,
        lastName: profileData.lastName || user.value.lastName,
        email: profileData.email || user.value.email,
        status: 'active', // Always set status to active when updating
        updatedAt: new Date().toISOString()
      };
      
      // Save to localStorage
      saveUserToStorage();
      
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
      // reset any previous error
      error.value = null;
      if (!file) {
        // If no file, remove the avatar
        user.value.avatar = null;
        avatarPreview.value = null;
        saveUserToStorage();
        return resolve(null);
      }

      // Validate file type
      if (!file.type.startsWith('image/')) {
        const err = new Error('Please select an image file');
        error.value = err.message;
        return reject(err);
      }

      // Validate file size (max 4MB to leave room for other user data)
      const maxSize = avatarMaxSizeBytes.value;
      if (file.size > maxSize) {
        const err = new Error(`Image size should be less than ${avatarMaxSizeMB.value}MB (current: ${(file.size / (1024 * 1024)).toFixed(2)}MB)`);
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
          user.value.updatedAt = new Date().toISOString();
          saveUserToStorage();
          resolve({ success: true, avatar: user.value.avatar });
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

  async function removeAvatar() {
    try {
      await new Promise(resolve => setTimeout(resolve, 100));
      
      // Clear both the avatar and preview
      avatarPreview.value = null;
      
      // Create a new user object to ensure reactivity
      const updatedUser = {
        ...user.value,
        avatar: null,
        updatedAt: new Date().toISOString()
      };
      
      // Update the user ref with the new object
      user.value = updatedUser;
      
      // Save to storage (and remove legacy avatar key)
      saveUserToStorage();
      localStorage.removeItem('user_avatar');
      
      // Force a re-render by updating the user reference
      user.value = { ...user.value };
      
      return { success: true };
    } catch (error) {
      console.error('Error removing avatar:', error);
      throw error;
    }
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
      user.value.updatedAt = new Date().toISOString();
      saveUserToStorage();
      
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

    // Clear any stored user data and legacy avatar cache
    localStorage.removeItem('userData');
    sessionStorage.removeItem('userData');
    localStorage.removeItem('user_avatar');

    // Reset to guest user and persist
    avatarPreview.value = null;
    user.value = getGuestUser();
    saveUserToStorage();
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
    avatarMaxSizeMB,
    
    // Getters
    userInitials,
    memberSinceFormatted,
    lastLoginFormatted,
    statusOptions,
    
    // Actions
    updateProfile,
    updateAvatar,
    removeAvatar,
    login,
    register,
    logout,
    initialize
  };
});
