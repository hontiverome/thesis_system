<!--
 * System Name: Theming and UI Framework
 * Module Name: Profile Module
 * Component Name: Edit Profile Modal
 * Purpose Of this file: 
 * To provide a modal interface for editing user profile information.
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
  <div class="profile-modal-overlay" @click.self="handleOverlayClick">
    <div class="profile-modal" ref="modal">
      <div class="profile-modal-content">
        <div class="profile-modal-header">
          <h3>Edit Profile</h3>
          <button 
            @click="$emit('close')" 
            class="profile-modal-close"
            aria-label="Close"
          >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
            </svg>
          </button>
        </div>
        
        <form @submit.prevent="handleSubmit" class="profile-modal-form">
          <div class="profile-form-group">
            <label for="name">Full Name</label>
            <input
              id="name"
              v-model="formData.name"
              type="text"
              required
              class="profile-form-control"
            />
          </div>
          
          <div class="profile-form-group">
            <label for="email">Email Address</label>
            <input
              id="email"
              v-model="formData.email"
              type="email"
              required
              class="profile-form-control"
            />
          </div>
          
          <div class="profile-form-actions">
            <button
              type="button"
              @click="$emit('close')"
              class="profile-btn profile-btn-modal"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="isSubmitting"
              class="profile-btn profile-btn-modal"
            >
              <span v-if="isSubmitting" class="profile-btn-loading">
                <svg class="profile-spinner" viewBox="0 0 24 24">
                  <circle class="profile-spinner-path" cx="12" cy="12" r="10"/>
                </svg>
                Saving...
              </span>
              <span v-else>Save Changes</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, watch, onMounted, onBeforeUnmount } from 'vue';
import { useUserStore } from '@/stores/user';

const modal = ref(null);

const handleClickOutside = (event) => {
  if (modal.value && !modal.value.contains(event.target)) {
    emit('close');
  }
};

onMounted(() => {
  document.addEventListener('mousedown', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('mousedown', handleClickOutside);
});

const handleOverlayClick = (event) => {
  if (event.target === event.currentTarget) {
    emit('close');
  }
};

const props = defineProps({
  user: {
    type: Object,
    required: true,
    default: () => ({
      name: '',
      email: '',
      status: 'active'
    })
  }
});

const emit = defineEmits(['close', 'saved']);
const userStore = useUserStore();
const isSubmitting = ref(false);

const formData = reactive({
  name: props.user?.name || '',
  email: props.user?.email || '',
  status: props.user?.status || 'active'
});

// Update form data when user prop changes
watch(() => props.user, (newUser) => {
  if (newUser) {
    formData.name = newUser.name || '';
    formData.email = newUser.email || '';
    formData.status = newUser.status || 'active';
  }
}, { immediate: true });

const handleSubmit = async () => {
  if (isSubmitting.value) return;
  
  try {
    isSubmitting.value = true;
    await userStore.updateProfile({
      name: formData.name.trim(),
      email: formData.email.trim(),
      status: formData.status
    });
    
    emit('saved');
    emit('close');
  } catch (error) {
    console.error('Error updating profile:', error);
    // Handle error (e.g., show error message)
  } finally {
    isSubmitting.value = false;
  }
};
</script>
