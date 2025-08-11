<!--
 * System Name: Human Resource Management Information System
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
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-8">
    <!-- Profile Header with Gradient Background -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-6 text-white">
      <div class="flex flex-col md:flex-row items-center">
        <!-- Avatar Section -->
        <div class="relative group mb-4 md:mb-0 md:mr-8">
          <div 
            class="w-32 h-32 rounded-full bg-white dark:bg-gray-700 flex items-center justify-center overflow-hidden border-4 border-white dark:border-gray-200 shadow-lg cursor-pointer"
            @click="$refs.fileInput.click()"
          >
            <img 
              v-if="user?.avatar" 
              :src="user.avatar" 
              :alt="user.name || 'Profile'"
              class="w-full h-full object-cover"
            />
            <div v-else class="text-4xl text-gray-400">
              {{ user?.name?.charAt(0) || 'U' }}
            </div>
            
            <!-- Upload Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
              <span class="text-white text-sm font-medium">Change Photo</span>
            </div>
          </div>
          
          <input 
            ref="fileInput"
            type="file" 
            class="hidden" 
            accept="image/*"
            @change="handleFileUpload"
          />
          
          <!-- Remove Avatar Button -->
          <button 
            v-if="user?.avatar"
            @click.stop="removeAvatar"
            class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors"
            title="Remove photo"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
        
        <!-- User Info -->
        <div class="text-center md:text-left">
          <h2 class="text-2xl md:text-3xl font-bold">
            {{ user?.name || 'User' }}
          </h2>
          
          <div class="flex items-center justify-center md:justify-start mt-2">
            <span 
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
              :class="statusClasses"
            >
              {{ statusText }}
            </span>
          </div>
          
          <p class="mt-2 text-blue-100">{{ user?.email || 'user@example.com' }}</p>
          
          <div class="mt-4 flex flex-wrap gap-2 justify-center md:justify-start">
            <button 
              @click="$emit('edit-profile')"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Edit Profile
            </button>
            
            <button 
              @click="$emit('update-status')"
              class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Update Status
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useUserStore } from '@/stores/user';

const props = defineProps({
  user: {
    type: Object,
    default: () => ({
      name: '',
      email: '',
      status: 'active',
      avatar: null
    })
  }
});

const emit = defineEmits(['edit-profile', 'update-status']);
const userStore = useUserStore();
const fileInput = ref(null);

const statusMap = {
  active: { text: 'Active', classes: 'bg-green-100 text-green-800' },
  away: { text: 'Away', classes: 'bg-yellow-100 text-yellow-800' },
  busy: { text: 'Busy', classes: 'bg-red-100 text-red-800' },
  offline: { text: 'Offline', classes: 'bg-gray-100 text-gray-800' }
};

const statusText = computed(() => statusMap[props.user?.status || 'active']?.text || 'Active');
const statusClasses = computed(() => statusMap[props.user?.status || 'active']?.classes || 'bg-gray-100 text-gray-800');

const handleFileUpload = async (event) => {
  const file = event.target.files?.[0];
  if (!file) return;
  
  try {
    await userStore.updateAvatar(file);
  } catch (error) {
    console.error('Error uploading avatar:', error);
    // Handle error (e.g., show error message)
  }
};

const removeAvatar = async () => {
  if (confirm('Are you sure you want to remove your profile picture?')) {
    try {
      await userStore.updateAvatar(null);
    } catch (error) {
      console.error('Error removing avatar:', error);
      // Handle error
    }
  }
};
</script>
