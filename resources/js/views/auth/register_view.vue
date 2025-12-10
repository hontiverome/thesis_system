<template>
  <div class="auth-page">
    <div class="auth-card">
      <div class="auth-header">
        <div class="icon-avatar">📝</div>
        <h1>Student Registration</h1>
        <p>Create your account using your Student Number</p>
      </div>

      <form @submit.prevent="handleRegister">
        <div class="form-group">
          <label>Student Number</label>
          <input 
            v-model="form.student_number"
            type="text" 
            class="form-control" 
            placeholder="202X-XXXXX-MN-0"
            required
          >
          <small class="form-text">Your email will be auto-generated as @iskolarngbayan.pup.edu.ph</small>
        </div>

        <div class="form-group">
          <label>Birth Date</label>
          <div class="date-row">
            <select v-model="form.birth_month" class="form-control" required>
              <option value="" disabled>Month</option>
              <option v-for="(m, index) in months" :key="index" :value="index + 1">{{ m }}</option>
            </select>
            <select v-model="form.birth_day" class="form-control" required>
              <option value="" disabled>Day</option>
              <option v-for="d in 31" :key="d" :value="d">{{ d }}</option>
            </select>
            <input 
              v-model="form.birth_year" 
              type="number" 
              class="form-control" 
              placeholder="Year"
              min="1900"
              max="2100"
              required
            >
          </div>
        </div>

        <div class="form-group">
          <label>Password</label>
          <input 
            v-model="form.password"
            type="password" 
            class="form-control" 
            required
          >
        </div>

        <div class="form-group">
          <label>Confirm Password</label>
          <input 
            v-model="form.password_confirmation"
            type="password" 
            class="form-control" 
            required
          >
        </div>

        <button type="submit" class="btn-primary" :disabled="loading">
          {{ loading ? 'Creating Account...' : 'Register' }}
        </button>

        <div class="login-link">
          Already have an account? 
          <router-link :to="{ name: 'login.student' }">Login here</router-link>
        </div>

        <div v-if="error" class="alert-error">{{ error }}</div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios'; // Direct axios call since useAuth might only have login

const router = useRouter();
const loading = ref(false);
const error = ref('');

const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

const form = reactive({
  student_number: '',
  birth_month: '',
  birth_day: '',
  birth_year: '',
  password: '',
  password_confirmation: ''
});

const handleRegister = async () => {
  loading.value = true;
  error.value = '';

  try {
    // Call the RegisterController endpoint
    await axios.post('/api/v1/auth/register', form);
    
    // On success, redirect to login or dashboard
    // Use 'login.student' to force them to login, or 'dashboard' if your API auto-logs them in
    router.push({ name: 'login.student' }); 
    alert('Registration successful! Please login.');
    
  } catch (err) {
    if (err.response && err.response.data && err.response.data.errors) {
       // Format validation errors (e.g., "The student number has already been taken.")
       error.value = Object.values(err.response.data.errors).flat().join('\n');
    } else {
       error.value = err.response?.data?.message || 'Registration failed.';
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
/* Reusing your auth styles */
.auth-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f8f9fa; padding: 1rem; }
.auth-card { background: white; padding: 2.5rem; border-radius: 16px; width: 100%; max-width: 450px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
.auth-header { text-align: center; margin-bottom: 2rem; }
.icon-avatar { font-size: 3rem; margin-bottom: 0.5rem; }
.form-group { margin-bottom: 1.25rem; }
.form-control { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; }
.form-text { font-size: 0.8rem; color: #666; margin-top: 0.25rem; display: block; }
.date-row { display: grid; grid-template-columns: 1fr 0.8fr 1fr; gap: 0.5rem; }
.btn-primary { width: 100%; padding: 0.875rem; background: #800000; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; margin-top: 1rem; }
.btn-primary:disabled { opacity: 0.7; }
.login-link { text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: #666; }
.login-link a { color: #800000; font-weight: 600; text-decoration: none; }
.login-link a:hover { text-decoration: underline; }
.alert-error { margin-top: 1rem; color: #dc2626; background: #fee2e2; padding: 0.75rem; border-radius: 8px; text-align: center; font-size: 0.9rem; white-space: pre-wrap; }
</style>