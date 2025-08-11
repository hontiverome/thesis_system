<!--
 * System Name: Theming and UI Framework
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
  <div class="profile-account-card">
    <div class="profile-account-header">
      <h3>Account Information</h3>
      <p>Manage your account details and preferences</p>
    </div>
    
    <div class="profile-account-content">
      <dl class="profile-account-details">
        <div class="profile-account-row">
          <dt>Full name</dt>
          <dd>
            {{ user?.name || 'Not provided' }}
          </dd>
        </div>
        
        <div class="profile-account-row">
          <dt>Email address</dt>
          <dd>
            {{ user?.email || 'Not provided' }}
          </dd>
        </div>
        
        <div class="profile-account-row">
          <dt>Account status</dt>
          <dd>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="statusClasses">
              {{ statusText }}
            </span>
          </dd>
        </div>
        
        <div class="profile-account-row">
          <dt>Member since</dt>
          <dd>
            {{ formatDate(user?.created_at) || 'Not available' }}
          </dd>
        </div>
        
        <div class="profile-account-row">
          <dt>Last updated</dt>
          <dd>
            {{ formatDate(user?.updated_at) || 'Not available' }}
          </dd>
        </div>
        
        <div class="profile-account-actions">
          <button
            @click="$emit('edit-profile')"
            class="profile-btn profile-btn-primary"
          >
            <svg class="profile-icon" viewBox="0 0 24 24">
              <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit Profile
          </button>
        </div>
      </dl>
    </div>
  </div>
</template>

<style scoped>
.profile-account-card {
  background-color: var(--card-bg);
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  margin-bottom: 1.5rem;
}

.profile-account-header {
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid var(--border-color);
}

.profile-account-header h3 {
  margin: 0;
  font-size: 1.125rem;
  font-weight: 600;
  color: var(--text-color);
}

.profile-account-header p {
  margin: 0.25rem 0 0;
  font-size: 0.875rem;
  color: var(--text-muted);
}

.profile-account-content {
  padding: 1.5rem;
}

.profile-account-details {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.profile-account-row {
  display: grid;
  grid-template-columns: 1fr;
  gap: 0.5rem;
}

@media (min-width: 640px) {
  .profile-account-row {
    grid-template-columns: 12rem 1fr;
    align-items: flex-start;
  }
}

.profile-account-row dt {
  font-size: 0.875rem;
  font-weight: 500;
  color: var(--text-muted);
}

.profile-account-row dd {
  margin: 0;
  font-size: 0.9375rem;
  color: var(--text-color);
  word-break: break-word;
}

.profile-account-actions {
  padding-top: 1.25rem;
  margin-top: 1.25rem;
  border-top: 1px solid var(--border-color);
}

.profile-btn {
  display: inline-flex;
  align-items: center;
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  border-radius: 0.375rem;
  transition: all 0.2s;
  cursor: pointer;
  border: 1px solid transparent;
}

.profile-btn-primary {
  background-color: var(--primary-color);
  color: white;
}

.profile-btn-primary:hover:not(:disabled) {
  background-color: var(--primary-dark);
}

.profile-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.profile-icon {
  width: 1.25rem;
  height: 1.25rem;
  margin-right: 0.5rem;
  fill: currentColor;
}
</style>

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
