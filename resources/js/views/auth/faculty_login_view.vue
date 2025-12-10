<template>
  <div class="auth-page">
    <div class="auth-card">
      <div class="auth-header">
        <div class="icon-avatar">👨‍🏫</div>
        <h1>Faculty Login</h1>
        <p>Authorized Personnel Only</p>
      </div>

      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label>Faculty ID</label>
          <input 
            v-model="form.faculty_id"
            type="text" 
            class="form-control" 
            required
          >
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
          {{ loading ? 'Authenticating...' : 'Login' }}
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

const form = reactive({
  faculty_id: '',
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
/* Using same styles as StudentLogin for consistency */
.auth-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f8f9fa; }
.auth-card { background: white; padding: 2.5rem; border-radius: 16px; width: 100%; max-width: 400px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
.auth-header { text-align: center; margin-bottom: 2rem; }
.icon-avatar { font-size: 3rem; margin-bottom: 0.5rem; }
.form-group { margin-bottom: 1.5rem; }
.form-control { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; }
.btn-primary { width: 100%; padding: 0.875rem; background: #800000; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
.btn-link { display: block; width: 100%; margin-top: 1rem; background: none; border: none; color: #666; cursor: pointer; text-decoration: underline; }
.alert-error { margin-top: 1rem; color: #dc2626; text-align: center; font-size: 0.9rem; }
</style>