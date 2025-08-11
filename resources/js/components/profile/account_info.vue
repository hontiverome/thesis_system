<!--
 * System Name: Human Resource Management Information System
 * Module Name: Profile Module
 * Component Name: Account Information
 * Purpose Of this file: 
 * To display and manage the user's account information including personal details and settings.
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
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
      <h3 class="text-lg font-medium text-gray-900 dark:text-white">Account Information</h3>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your account details and preferences</p>
    </div>
    
    <div class="px-6 py-5">
      <dl class="space-y-8">
        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Full name</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
            {{ user?.name || 'Not provided' }}
          </dd>
        </div>
        
        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email address</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
            {{ user?.email || 'Not provided' }}
          </dd>
        </div>
        
        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Account status</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="statusClasses">
              {{ statusText }}
            </span>
          </dd>
        </div>
        
        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Member since</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
            {{ user?.created_at ? formatDate(user.created_at) : 'N/A' }}
          </dd>
        </div>
        
        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last updated</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
            {{ user?.updated_at ? formatDate(user.updated_at) : 'N/A' }}
          </dd>
        </div>
      </dl>
    </div>
    
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-right">
      <button 
        @click="$emit('edit-profile')"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
        Edit Profile
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  user: {
    type: Object,
    default: () => ({
      name: '',
      email: '',
      status: 'active',
      created_at: null,
      updated_at: null
    })
  }
});

const emit = defineEmits(['edit-profile']);

const statusMap = {
  active: { text: 'Active', classes: 'bg-green-100 text-green-800' },
  away: { text: 'Away', classes: 'bg-yellow-100 text-yellow-800' },
  busy: { text: 'Busy', classes: 'bg-red-100 text-red-800' },
  offline: { text: 'Offline', classes: 'bg-gray-100 text-gray-800' }
};

const statusText = computed(() => statusMap[props.user?.status || 'active']?.text || 'Active');
const statusClasses = computed(() => statusMap[props.user?.status || 'active']?.classes || 'bg-gray-100 text-gray-800');

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};
</script>
