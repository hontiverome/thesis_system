<template>
  <div class="auth-page page-view">
    <div class="auth-container">
      <header class="auth-header">
        <div class="icon-header">Faculty</div> <!--PUP/CPE LOGO-->
        <h1 class="page-title">Faculty Login</h1>
        <p class="page-subtitle">Advisers, Panelists & Staff Portal</p>
      </header>
      
      <form class="auth-form" @submit.prevent="handleLogin">
        <div class="form-group">
          <label class="form-label">Faculty ID</label>
          <div class="input-wrapper">
            <i class="icon icon-badge"></i> <input
              v-model="form.faculty_id"
              type="text"
              required
              class="form-control"
              placeholder="e.g., FAC-001"
              :disabled="loading"
            >
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Password</label>
          <div class="input-wrapper">
            <i class="icon icon-lock"></i>
            <input
              v-model="form.password"
              type="password"
              required
              class="form-control"
              placeholder="Enter your password"
              :disabled="loading"
            >
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-lg" :disabled="loading">
          <span v-if="loading">Verifying Access...</span>
          <span v-else>Login as Faculty</span>
        </button>

        <div class="links-container">
          <router-link :to="{ name: 'access-portal' }" class="link">Back to Portal</router-link>
        </div>
      </form>

      <transition name="fade">
        <div v-if="error" class="alert alert-error mt-3">
          <i class="icon icon-alert-circle"></i>
          {{ error }}
        </div>
      </transition>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useAuth } from '@/composables/useAuth';

const { login } = useAuth();
const loading = ref(false);
const error = ref('');

const form = reactive({
  faculty_id: '', // Backend expects 'faculty_id' to trigger faculty logic
  password: ''
});

const handleLogin = async () => {
  loading.value = true;
  error.value = '';
  
  try {
    // Pass the form data to useAuth.js
    await login(form);
    
    // Redirect on success
    window.location.href = '/dashboard';
  } catch (err) {
    if (err.response && err.response.data && err.response.data.message) {
         error.value = err.response.data.message;
    } else if (err.message) {
         error.value = err.message;
    } else {
         error.value = 'Login failed. Please check your Faculty ID and password.';
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
/* Page Layout */
.auth-page {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background-color: var(--bg-color, #f3f4f6);
  padding: 1rem;
}

.auth-container {
  width: 100%;
  max-width: 420px;
  background: white;
  border-radius: 12px;
  padding: 2.5rem;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
}

/* Header */
.auth-header { text-align: center; margin-bottom: 2rem; }
.icon-header { font-size: 3rem; margin-bottom: 0.5rem; }
.page-title { font-size: 1.5rem; font-weight: 700; color: #111827; margin: 0; }
.page-subtitle { color: #6b7280; font-size: 0.95rem; margin-top: 0.5rem; }

/* Forms */
.form-group { margin-bottom: 1.5rem; }
.form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151; font-size: 0.95rem; }
.form-control {
  width: 100%;
  padding: 0.75rem 1rem 0.75rem 2.75rem; /* Left padding space for icon */
  border: 1px solid #d1d5db;
  border-radius: 0.5rem;
  font-size: 1rem;
  transition: border-color 0.2s;
}
.form-control:focus { outline: none; border-color: #4f46e5; ring: 2px solid #4f46e5; }
.form-control:disabled { background-color: #f3f4f6; cursor: not-allowed; }

/* Icons in Inputs */
.input-wrapper { position: relative; }
.input-wrapper .icon {
  position: absolute;
  left: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: #9ca3af;
  pointer-events: none;
  font-style: normal;
  /* If using iconify or similar, ensure basic styling here */
  width: 1.25em; 
  height: 1.25em;
  background-size: contain; 
}
/* Fallback if no icon library: simple character or background */
.icon-badge::before { content: '👤'; } 
.icon-lock::before { content: '🔒:'; }
.icon-alert-circle::before { content: '⚠️'; margin-right: 0.5rem; }

/* Buttons */
.btn-primary {
  width: 100%;
  background-color: #4f46e5;
  color: white;
  padding: 0.875rem;
  border-radius: 0.5rem;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: background-color 0.2s;
}
.btn-primary:hover:not(:disabled) { background-color: #4338ca; }
.btn-primary:disabled { opacity: 0.7; cursor: wait; }

/* Links */
.links-container { margin-top: 1.5rem; text-align: center; font-size: 0.9rem; }
.link { color: #4f46e5; text-decoration: none; font-weight: 500; }
.link:hover { text-decoration: underline; }

/* Alerts */
.alert-error {
  background-color: #fef2f2;
  color: #991b1b;
  padding: 0.75rem;
  border-radius: 0.5rem;
  font-size: 0.9rem;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #fee2e2;
}

/* Transitions */
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>