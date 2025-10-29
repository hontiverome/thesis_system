<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Sign in to your account
        </h2>
      </div>
      
      <form class="mt-8 space-y-6" @submit.prevent="handleLogin">
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="email-address" class="sr-only">Email address</label>
            <input 
              id="email-address"
              v-model="form.email"
              name="email" 
              type="email" 
              autocomplete="email" 
              required 
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 sm:text-sm" 
              placeholder="Email address"
            >
          </div>
          <div>
            <label for="password" class="sr-only">Password</label>
            <input 
              id="password" 
              v-model="form.password"
              name="password" 
              type="password" 
              autocomplete="current-password" 
              required 
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 sm:text-sm" 
              placeholder="Password"
            >
          </div>
        </div>

        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input 
              id="remember-me" 
              v-model="form.remember"
              name="remember-me" 
              type="checkbox" 
              class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
            >
            <label for="remember-me" class="ml-2 block text-sm text-gray-900">
              Remember me
            </label>
          </div>

          <div class="text-sm">
            <a href="#" class="font-medium text-primary-600 hover:text-primary-500">
              Forgot your password?
            </a>
          </div>
        </div>

        <div>
          <button 
            type="submit" 
            :disabled="loading"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
              <svg v-if="!loading" class="h-5 w-5 text-primary-500 group-hover:text-primary-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
              </svg>
              <svg v-else class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
            {{ loading ? 'Signing in...' : 'Sign in' }}
          </button>
        </div>
      </form>
      
      <!-- Debug Info (temporary) -->
      <div v-if="isDev" class="mt-4 p-4 bg-gray-100 text-xs text-gray-600 rounded-md">
        <div>Error state: {{ error ? 'true' : 'false' }}</div>
        <div>Error message: {{ error || 'No error' }}</div>
        <div>Loading: {{ loading ? 'true' : 'false' }}</div>
      </div>

      <!-- Success/Error Messages -->
      <div v-if="successMessage" class="mt-4 p-4 bg-green-50 text-green-700 rounded-md">
        {{ successMessage }}
      </div>
      <div v-else-if="error" class="mt-4 p-4 bg-red-50 text-red-700 rounded-md">
        {{ error }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuth } from '@/composables/useAuth';

// Debug mode
const isDev = import.meta.env.DEV;

const router = useRouter();
const route = useRoute();
const { login, isAuthenticated } = useAuth();

const loading = ref(false);
const error = ref('');
const successMessage = ref('');

const form = reactive({
  email: '',
  password: '',
  remember: false
});

// Redirect if already logged in
onMounted(() => {
  if (isAuthenticated.value) {
    router.push(route.query.redirect || '/dashboard');
  }
});

const handleLogin = async () => {
  if (loading.value) return;
  
  try {
    loading.value = true;
    error.value = '';
    successMessage.value = '';
    
    console.log('Attempting to login with:', form.email);
    
    const response = await login({
      email: form.email,
      password: form.password,
      remember: form.remember
    });
    
    console.log('Login response:', response);
    
    if (response && response.token) {
      console.log('Login successful, token received');
      successMessage.value = 'Login successful! Redirecting...';
      
      // Clear form
      form.email = '';
      form.password = '';
      
      // Short delay to show success message and ensure state is updated
      await new Promise(resolve => setTimeout(resolve, 500));
      
      // Get the redirect path or default to dashboard
      const redirectPath = route.query.redirect || '/dashboard';
      console.log('Redirecting to:', redirectPath);
      
      // Force a full page reload to ensure all auth state is properly initialized
      window.location.href = redirectPath;
    } else {
      throw new Error('Invalid response from server');
    }
    
  } catch (err) {
    // Clear success message if there was an error after a success
    successMessage.value = '';
    console.error('Login error:', err);
    
    // Ensure error is displayed even if not caught by useAuth
    if (!error.value && err.message) {
      error.value = err.message;
    }
  } finally {
    loading.value = false;
  }
};
</script>
