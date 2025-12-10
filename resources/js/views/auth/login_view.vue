<!---
 * System Name: Theming and UI Framework
 * Module Name: Authentication
 * Purpose Of this file: 
 * Provides user authentication interface with email/password login
 * 
 * Author: Jerome Andrei O. Hontiveros
 * Copyright (C) 2025
 * by the Backend Team
 * All rights reserved.
-->

<template>
  <div class="auth-page page-view">
    <div class="auth-container">
      <!-- Header Section -->
      <header class="auth-header">
        <h1 id="page-title" class="page-title">Welcome Back</h1>
        <p class="page-subtitle">Sign in to access your account</p>
      </header>
      
      <!-- Main Form Section -->
      <form class="auth-form" @submit.prevent="handleLogin" novalidate>
        <!-- Email Input -->
        <div class="form-group">
          <label for="email" class="form-label">Email Address</label>
          <div class="input-wrapper">
            <i class="icon icon-mail"></i>
            <input
              id="email"
              v-model="form.email"
              type="email"
              name="email"
              autocomplete="email"
              required
              class="form-control"
              placeholder="Enter your email"
              :disabled="loading"
              aria-describedby="email-help"
            >
          </div>
          <small id="email-help" class="form-text">We'll never share your email with anyone else.</small>
        </div>

        <!-- Password Input -->
        <div class="form-group">
          <div class="d-flex justify-content-between align-items-center">
            <label for="password" class="form-label">Password</label>
            <router-link 
              to="/forgot-password" 
              class="forgot-password"
              :class="{ 'disabled': loading }"
              tabindex="-1"
            >
              Forgot password?
            </router-link>
          </div>
          <div class="input-wrapper">
            <i class="icon icon-lock"></i>
            <input
              id="password"
              v-model="form.password"
              type="password"
              name="password"
              autocomplete="current-password"
              required
              class="form-control"
              placeholder="Enter your password"
              :disabled="loading"
            >
            <button 
              type="button" 
              class="btn btn-icon password-toggle"
              @click="togglePasswordVisibility"
              :disabled="loading"
              :aria-label="showPassword ? 'Hide password' : 'Show password'"
            >
              <i :class="showPassword ? 'icon-eye-off' : 'icon-eye'"></i>
            </button>
          </div>
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="form-group form-check">
          <input
            id="remember"
            v-model="form.remember"
            type="checkbox"
            class="form-check-input"
            :disabled="loading"
          >
          <label class="form-check-label" for="remember">
            Remember me
          </label>
        </div>

        <!-- Submit Button -->
        <button 
          type="submit" 
          class="btn btn-primary btn-block btn-lg"
          :class="{ 'btn-loading': loading }"
          :disabled="loading"
        >
          <span class="btn-content">
            <i class="icon icon-log-in"></i>
            <span>{{ loading ? 'Signing in...' : 'Sign In' }}</span>
          </span>
          <span class="btn-loader" v-if="loading">
            <span class="spinner"></span>
          </span>
        </button>

        <!-- Social Login Options -->
        <div class="divider">
          <span>or continue with</span>
        </div>

        <div class="social-login">
          <button type="button" class="btn btn-outline-secondary btn-icon" :disabled="loading">
            <i class="icon icon-github"></i>
          </button>
          <button type="button" class="btn btn-outline-secondary btn-icon" :disabled="loading">
            <i class="icon icon-google"></i>
          </button>
          <button type="button" class="btn btn-outline-secondary btn-icon" :disabled="loading">
            <i class="icon icon-microsoft"></i>
          </button>
        </div>

        <!-- Registration Link -->
        <p class="text-center mt-4">
          Don't have an account? 
          <router-link to="/register" :class="{ 'disabled': loading }" tabindex="-1">
            Create one
          </router-link>
        </p>
      </form>

      <!-- Status Messages -->
      <transition name="fade">
        <div v-if="successMessage" class="alert alert-success" role="alert">
          <i class="icon icon-check-circle"></i>
          {{ successMessage }}
        </div>
      </transition>

      <transition name="fade">
        <div v-if="error" class="alert alert-error" role="alert">
          <i class="icon icon-alert-circle"></i>
          {{ error }}
        </div>
      </transition>
    </div>

    <!-- Debug Info (Development Only) -->
    <div v-if="isDev" class="debug-info">
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuth } from '@/composables/useAuth';
import { useThemeStore } from '@/stores/theme';

// Component state
const router = useRouter();
const route = useRoute();
const themeStore = useThemeStore();
const { login, isAuthenticated } = useAuth();

// Debug mode
const isDev = import.meta.env.DEV;

// UI State
const loading = ref(false);
const error = ref('');
const successMessage = ref('');
const showPassword = ref(false);

// Form data
const form = reactive({
  email: '',
  password: '',
  remember: false
});

// Computed properties
const currentTheme = computed(() => {
  return themeStore.availableThemes.find(t => t.id === themeStore.currentTheme) || {};
});

// Toggle password visibility
const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value;
  const passwordInput = document.getElementById('password');
  if (passwordInput) {
    passwordInput.type = showPassword.value ? 'text' : 'password';
  }
};

// Redirect if already logged in
onMounted(() => {
  if (isAuthenticated.value) {
    const redirectPath = route.query.redirect || '/dashboard';
    router.push(redirectPath);
  }
  
  // Auto-focus email field on mount
  const emailInput = document.getElementById('email');
  if (emailInput) emailInput.focus();
});

/**
 * Handle login form submission
 */
const handleLogin = async () => {
  if (loading.value) return;
  
  try {
    // Reset states
    loading.value = true;
    error.value = '';
    successMessage.value = '';
    
    // Validate form
    if (!form.email || !form.password) {
      error.value = 'Please fill in all required fields';
      return;
    }
    
    // Attempt login
    const response = await login({
      email: form.email.trim(),
      password: form.password,
      remember: form.remember
    });
    
    // Handle successful login
    if (response?.token) {
      successMessage.value = 'Login successful! Redirecting...';
      
      // Clear sensitive data
      form.password = '';
      
      // Short delay to show success message
      await new Promise(resolve => setTimeout(resolve, 800));
      
      // Redirect to intended path or dashboard
      const redirectPath = route.query.redirect || '/dashboard';
      window.location.href = redirectPath;
    } else {
      throw new Error('Invalid response from server');
    }
    
  } catch (err) {
    console.error('Login error:', err);
    
    // User-friendly error messages
    const errorMap = {
      'auth/invalid-email': 'Please enter a valid email address',
      'auth/user-disabled': 'This account has been disabled',
      'auth/user-not-found': 'No account found with this email',
      'auth/wrong-password': 'Incorrect email or password',
      'network-request-failed': 'Network error. Please check your connection.'
    };
    
    error.value = errorMap[err.code] || err.message || 'Login failed. Please try again.';
    
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
/* Auth Page Layout */
.auth-page {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  padding: 2rem;
  background-color: var(--bg-color);
  background-image: var(--auth-bg, none);
  background-size: cover;
  background-position: center;
  transition: all 0.3s ease;
}

.auth-container {
  width: 100%;
  max-width: 420px;
  background: var(--card-bg);
  border-radius: var(--border-radius-lg);
  box-shadow: var(--shadow-lg);
  padding: 2.5rem;
  position: relative;
  overflow: hidden;
}

/* Header Styles */
.auth-header {
  text-align: center;
  margin-bottom: 2rem;
}

.page-title {
  font-size: 1.75rem;
  font-weight: 700;
  color: var(--text-primary);
  margin: 0 0 0.5rem;
}

.page-subtitle {
  color: var(--text-secondary);
  font-size: 1rem;
  margin: 0;
}

/* Form Styles */
.auth-form {
  margin-top: 1.5rem;
}

.form-group {
  margin-bottom: 1.25rem;
}

.form-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: var(--text-primary);
}

.input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.icon {
  position: absolute;
  left: 1rem;
  color: var(--text-muted);
  pointer-events: none;
  transition: color 0.2s;
}

.form-control {
  width: 100%;
  padding: 0.75rem 1rem 0.75rem 3rem;
  font-size: 1rem;
  line-height: 1.5;
  color: var(--text-primary);
  background-color: var(--input-bg);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius);
  transition: border-color 0.2s, box-shadow 0.2s;
}

.form-control:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 2px rgba(var(--primary-rgb), 0.1);
  outline: none;
}

.form-control:disabled {
  background-color: var(--disabled-bg);
  cursor: not-allowed;
}

/* Password Toggle */
.password-toggle {
  position: absolute;
  right: 0.75rem;
  background: none;
  border: none;
  color: var(--text-muted);
  cursor: pointer;
  padding: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: color 0.2s;
}

.password-toggle:hover {
  color: var(--primary-color);
}

.password-toggle:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Remember Me */
.form-check {
  display: flex;
  align-items: center;
  margin: 1.5rem 0;
}

.form-check-input {
  width: 1.25rem;
  height: 1.25rem;
  margin-right: 0.5rem;
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-sm);
  background-color: var(--input-bg);
  transition: all 0.2s;
}

.form-check-input:checked {
  background-color: var(--primary-color);
  border-color: var(--primary-color);
}

.form-check-label {
  color: var(--text-secondary);
  font-size: 0.9375rem;
  cursor: pointer;
}

/* Forgot Password Link */
.forgot-password {
  font-size: 0.875rem;
  color: var(--primary-color);
  text-decoration: none;
  transition: opacity 0.2s;
}

.forgot-password:hover {
  text-decoration: underline;
  opacity: 0.9;
}

.forgot-password.disabled {
  pointer-events: none;
  opacity: 0.6;
}

/* Submit Button */
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.75rem 1.5rem;
  font-size: 1rem;
  font-weight: 600;
  line-height: 1.5;
  text-align: center;
  text-decoration: none;
  white-space: nowrap;
  border: 1px solid transparent;
  border-radius: var(--border-radius);
  cursor: pointer;
  transition: all 0.2s;
  position: relative;
  overflow: hidden;
}

.btn-primary {
  color: white;
  background-color: var(--primary-color);
  border-color: var(--primary-color);
}

.btn-primary:hover:not(:disabled) {
  background-color: var(--primary-dark);
  border-color: var(--primary-dark);
}

.btn-block {
  display: block;
  width: 100%;
}

.btn-lg {
  padding: 0.875rem 1.5rem;
  font-size: 1.1rem;
}

.btn-content {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.btn-loader {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: inherit;
  opacity: 0;
  transition: opacity 0.2s;
}

.btn-loading .btn-content {
  opacity: 0;
}

.btn-loading .btn-loader {
  opacity: 1;
}

.spinner {
  width: 1.5rem;
  height: 1.5rem;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top-color: white;
  animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

/* Divider */
.divider {
  display: flex;
  align-items: center;
  text-align: center;
  margin: 1.5rem 0;
  color: var(--text-muted);
  font-size: 0.875rem;
}

.divider::before,
.divider::after {
  content: '';
  flex: 1;
  border-bottom: 1px solid var(--border-color);
}

.divider:not(:empty)::before {
  margin-right: 1rem;
}

.divider:not(:empty)::after {
  margin-left: 1rem;
}

/* Social Login */
.social-login {
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin: 1.5rem 0;
}

.btn-icon {
  width: 2.75rem;
  height: 2.75rem;
  padding: 0;
  border-radius: 50%;
  font-size: 1.25rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.btn-outline-secondary {
  color: var(--text-secondary);
  background-color: transparent;
  border: 1px solid var(--border-color);
}

.btn-outline-secondary:hover:not(:disabled) {
  background-color: var(--hover-bg);
  border-color: var(--border-color-hover);
}

/* Alert Messages */
.alert {
  padding: 0.75rem 1rem;
  border-radius: var(--border-radius);
  margin: 1rem 0;
  display: flex;
  align-items: flex-start;
  gap: 0.5rem;
  font-size: 0.9375rem;
  line-height: 1.5;
}

.alert i {
  font-size: 1.25rem;
  flex-shrink: 0;
  margin-top: 0.125rem;
}

.alert-success {
  background-color: var(--success-bg);
  color: var(--success-text);
}

.alert-error {
  background-color: var(--error-bg);
  color: var(--error-text);
}

/* Debug Info */
.debug-info {
  position: fixed;
  bottom: 1rem;
  right: 1rem;
  background: rgba(0, 0, 0, 0.8);
  color: #fff;
  padding: 0.5rem;
  border-radius: 4px;
  font-size: 0.75rem;
  max-width: 300px;
  max-height: 200px;
  overflow: auto;
  z-index: 1000;
}

/* Animations */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Responsive Adjustments */
@media (max-width: 480px) {
  .auth-container {
    padding: 1.5rem;
  }
  
  .page-title {
    font-size: 1.5rem;
  }
  
  .btn-lg {
    padding: 0.75rem 1.25rem;
    font-size: 1rem;
  }
}
</style>
