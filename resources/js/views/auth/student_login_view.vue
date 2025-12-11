<template>
  <div class="auth-page page-view">
    <div class="auth-container">
      <header class="auth-header">
        <div class="icon-header">👨‍🎓</div>
        <h1 class="page-title">Student Login</h1>
        <p class="page-subtitle">Enter your details to access the student dashboard</p>
      </header>
      
      <form class="auth-form" @submit.prevent="handleLogin">
        <div class="form-group">
          <label class="form-label">Student Number</label>
          <div class="input-wrapper">
            <i class="icon icon-user"></i>
            <input v-model="form.student_number" type="text" required class="form-control" placeholder="e.g., 2023-00001-MN-0" :disabled="loading">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Birth Date</label>
          <div class="date-grid">
            <select v-model="form.birth_month" class="form-control" required :disabled="loading">
              <option value="" disabled selected>Month</option>
              <option v-for="m in 12" :key="m" :value="m">{{ m }}</option>
            </select>
            <input v-model="form.birth_day" type="number" min="1" max="31" class="form-control text-center" placeholder="Day" required :disabled="loading">
            <input v-model="form.birth_year" type="number" min="1900" :max="new Date().getFullYear()" class="form-control text-center" placeholder="Year" required :disabled="loading">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Password</label>
          <div class="input-wrapper">
            <i class="icon icon-lock"></i>
            <input v-model="form.password" type="password" required class="form-control" placeholder="Enter your password" :disabled="loading">
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-lg" :disabled="loading">
          <span v-if="loading">Logging in...</span>
          <span v-else>Login</span>
        </button>

        <div class="links-container">
          <router-link :to="{ name: 'register' }" class="link">Create Account</router-link>
          <span class="divider">•</span>
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
  student_number: '',
  birth_month: '',
  birth_day: '',
  birth_year: '',
  password: ''
});

const handleLogin = async () => {
  loading.value = true;
  error.value = '';
  
  try {
    await login(form);
    // CRITICAL: Redirect to the STUDENT SPECIFIC dashboard
    // Using window.location.href ensures the Navbar loads correctly
    window.location.href = '/student-dashboard'; 
  } catch (err) {
    if (err.response && err.response.data && err.response.data.message) {
         error.value = err.response.data.message;
    } else {
         error.value = 'Login failed. Please check your credentials.';
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
/* Keep your existing styles here */
.auth-page { display: flex; align-items: center; justify-content: center; min-height: 100vh; background-color: var(--bg-color, #f3f4f6); padding: 1rem; }
.auth-container { width: 100%; max-width: 440px; background: white; border-radius: 12px; padding: 2.5rem; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); }
.auth-header { text-align: center; margin-bottom: 2rem; }
.icon-header { font-size: 3rem; margin-bottom: 0.5rem; }
.page-title { font-size: 1.5rem; font-weight: 700; color: #111827; }
.page-subtitle { color: #6b7280; font-size: 0.95rem; margin-top: 0.5rem; }
.form-group { margin-bottom: 1.5rem; }
.form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
.form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; }
.date-grid { display: grid; grid-template-columns: 2fr 1fr 1.2fr; gap: 0.75rem; }
.input-wrapper { position: relative; }
.input-wrapper .icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af; }
.input-wrapper input { padding-left: 2.75rem; }
.btn-primary { width: 100%; background-color: #4f46e5; color: white; padding: 0.875rem; border-radius: 0.5rem; font-weight: 600; border: none; cursor: pointer; }
.links-container { margin-top: 1.5rem; text-align: center; font-size: 0.9rem; }
.link { color: #4f46e5; text-decoration: none; }
.divider { margin: 0 0.5rem; color: #d1d5db; }
.alert-error { background-color: #fef2f2; color: #991b1b; padding: 0.75rem; border-radius: 0.5rem; margin-top: 1rem; display: flex; align-items: center; gap: 0.5rem; border: 1px solid #fee2e2; }
</style>