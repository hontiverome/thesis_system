<!--
 * System Name: Human Resource Management Information System
 * Module Name: Profile Module
 * Purpose Of this file: 
 * To display and manage user profile information including personal details and account settings.
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
  <div class="container mx-auto px-4 py-8">
    <!-- Loading State -->
    <div v-if="userStore.isLoading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg flex items-center space-x-4">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
        <span class="text-gray-700 dark:text-gray-200">Loading profile...</span>
      </div>
    </div>

    <!-- Error Message -->
    <div v-if="error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
      <p>{{ error }}</p>
    </div>

    <!-- Profile Header -->
    <ProfileHeader 
      :user="userStore.user"
      @edit-profile="showEditModal = true"
    />

    <!-- Account Information -->
    <AccountInfo 
      :user="userStore.user" 
      class="mt-8"
      @edit-profile="showEditModal = true"
    />

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
import { useUserStore } from '@/stores/user';
import ProfileHeader from '@/components/profile/profile_header.vue';
import AccountInfo from '@/components/profile/account_info.vue';
import EditProfileModal from '@/components/profile/edit_profile_modal.vue';

const userStore = useUserStore();
const showEditModal = ref(false);
const error = ref<string | null>(null);

// Initialize user data
const initialize = async (): Promise<void> => {
  try {
    await userStore.initialize();
  } catch (err) {
    console.error('Failed to load profile:', err);
    error.value = 'Failed to load profile data. Please try again later.';
  }
};

const handleProfileSaved = (): void => {
  showEditModal.value = false;
  initialize();
};

// Load data when component mounts
onMounted((): void => {
  initialize();
});
</script>
