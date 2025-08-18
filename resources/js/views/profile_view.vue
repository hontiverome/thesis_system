<!---
 * System Name: Theming and UI Framework
 * Module Name: Profile
 * Purpose Of this file: 
 * To display and manage user profile information and settings.
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
  <div class="profile" role="main" aria-labelledby="profile-title">
    <!-- Loading State -->
    <div v-if="userStore.isLoading" class="loading-overlay">
      <div class="loading-content">
        <div class="spinner"></div>
        <span>Loading profile...</span>
      </div>
    </div>

    <!-- Error Message -->
    <div v-else-if="error" class="error-message">
      <div class="error-content">
        <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
        <h1 class="text-2xl font-bold text-gray-900">{{ userStore.user?.firstName || 'User' }} {{ userStore.user?.lastName || '' }}</h1>
        <p>{{ error }}</p>
      </div>
    </div>

    <!-- Main Content -->
    <div v-else class="profile-content">
      <!-- Profile Header Component -->
      <ProfileHeader 
        @edit-profile="showEditModal = true"
      />

      <!-- Account Information Card -->
      <div class="account-info-card">
        <div class="card-content">
          <AccountInfo 
            @edit-profile="showEditModal = true"
          />
        </div>
      </div>
    </div>

    <!-- Edit Profile Modal -->
    <EditProfileModal 
      v-if="showEditModal"
      :user="userStore.user"
      @close="showEditModal = false"
      @saved="handleProfileSaved"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useUserStore } from '@/stores/user.js';
import ProfileHeader from '../components/profile/profile_header.vue';
import AccountInfo from '../components/profile/account_info.vue';
import EditProfileModal from '../components/profile/edit_profile_modal.vue';

const userStore = useUserStore();
const showEditModal = ref(false);
const error = ref<string | null>(null);

// Initialize user data
const initialize = async (): Promise<void> => {
  try {
    // Only initialize if we don't have user data yet
    if (!userStore.user || !userStore.user.id) {
      await userStore.initialize();
    }
  } catch (err) {
    console.error('Failed to load profile:', err);
    error.value = 'Failed to load profile data. Please try again later.';
  }
};

const handleProfileSaved = async (): Promise<void> => {
  // Any additional logic after profile is saved
  showEditModal.value = false;
  
  // Refresh user data to ensure everything is up to date
  try {
    await userStore.initialize();
  } catch (err: unknown) {
    console.error('Error refreshing user data:', err);
  }
};

// Load data when component mounts
onMounted((): void => {
  initialize();
});
</script>
