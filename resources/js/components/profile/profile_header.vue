<!--
 * System Name: Theming and UI Framework
 * Module Name: Profile Module
 * Component Name: Profile Header
 * Purpose Of this file: 
 * To display the user's profile header section including avatar, name, and action buttons.
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
-->

<template>
  <div class="profile-header-card">
    <div class="profile-header-banner">
      <div class="profile-header-content">
        <!-- Avatar Section -->
        <div class="profile-avatar-wrapper">
          <div class="profile-avatar-container">
            <div 
              class="profile-avatar"
              @click="$refs.fileInput.click()"
              :key="user?.avatar || 'no-avatar'"
            >
              <img 
                v-if="user?.avatar" 
                :src="user.avatar" 
                :alt="user.name || 'Profile'"
                class="profile-avatar-img"
              />
              <div v-else class="profile-avatar-initials">
                {{ user?.name?.charAt(0) || 'U' }}
              </div>
              
              <!-- Upload Overlay -->
              <div class="profile-avatar-overlay">
                <span>Change Photo</span>
              </div>
            </div>
            
            <input 
              ref="fileInput"
              type="file" 
              class="file-input" 
              accept="image/*"
              @change="handleFileUpload"
            />
          </div>
          
          <!-- Remove Avatar Button -->
          <div v-if="user?.avatar" class="remove-avatar-container">
            <button 
              @click.stop="removeAvatar"
              class="remove-avatar-btn"
              title="Remove photo"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
              </svg>
              <span>Remove Photo</span>
            </button>
          </div>
        </div>
        
        <!-- User Info -->
        <header class="profile-header">
          <h2 class=profile-name>{{ user?.name || 'User' }}</h2>
          <div class="profile-email">{{ user?.email || 'user@example.com' }}</div>
        </header>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useUserStore } from '../../stores/user';

const emit = defineEmits(['edit-profile']);
const userStore = useUserStore();
const fileInput = ref(null);

// Use the user from the store
const user = computed(() => userStore.user);

const statusText = computed(() => {
  const statusMap = {
    active: 'Active',
    away: 'Away',
    busy: 'Busy',
    offline: 'Offline'
  };
  return statusMap[user.value?.status] || 'Active';
});

const handleFileUpload = async (event) => {
  const file = event.target.files?.[0];
  if (!file) return;
  
  try {
    await userStore.updateAvatar(file);
  } catch (error) {
    console.error('Error uploading avatar:', error);
  } finally {
    // Reset the file input
    event.target.value = '';
  }
};

const removeAvatar = async () => {
  if (!confirm('Are you sure you want to remove your profile photo?')) return;
  
  try {
    await userStore.removeAvatar();
    // Force a re-render by creating a new user object reference
    userStore.user = { ...userStore.user };
  } catch (error) {
    console.error('Error removing avatar:', error);
  }
};
</script>
