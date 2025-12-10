<template>
  <div class="auth-page">
    <div class="auth-card">
      <div class="auth-header">
        <div class="icon-avatar">👨‍🎓</div>
        <h1>Student Login</h1>
        <p>Enter your Student Number and Birth Date</p>
      </div>

      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label>Student Number</label>
          <input 
            v-model="form.student_number"
            type="text" 
            class="form-control" 
            placeholder="202X-XXXXX-MN-0"
            required
          >
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

        <button type="submit" class="btn-primary" :disabled="loading">
          {{ loading ? 'Verifying...' : 'Login' }}
        </button>

        <button type="button" class="btn-link" @click="$router.push('/portal')">
          Change User Type
        </button>

        <div v-if="error" class="alert-error">{{ error }}</div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '@/composables/useAuth';

const router = useRouter();
const { login } = useAuth();
const loading = ref(false);
const error = ref('');

const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

const form = reactive({
  student_number: '',
  birth_month: '',
  birth_day: '',
  birth_year: '',
  password: '',
  device_name: 'web-browser'
});

const handleLogin = async () => {
  loading.value = true;
  error.value = '';
  try {
    await login(form);
    router.push('/dashboard');
  } catch (err) {
    error.value = err.response?.data?.message || 'Login failed.';
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
/* Common auth styles */
.auth-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f8f9fa; }
.auth-card { background: white; padding: 2.5rem; border-radius: 16px; width: 100%; max-width: 400px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
.auth-header { text-align: center; margin-bottom: 2rem; }
.icon-avatar { font-size: 3rem; margin-bottom: 0.5rem; }
.form-group { margin-bottom: 1.5rem; }
.form-control { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; }
.date-row { display: grid; grid-template-columns: 1fr 0.8fr 1fr; gap: 0.5rem; }
.btn-primary { width: 100%; padding: 0.875rem; background: #800000; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
.btn-link { display: block; width: 100%; margin-top: 1rem; background: none; border: none; color: #666; cursor: pointer; text-decoration: underline; }
.alert-error { margin-top: 1rem; color: #dc2626; text-align: center; font-size: 0.9rem; }
</style>