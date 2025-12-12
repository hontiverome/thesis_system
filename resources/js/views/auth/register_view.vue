<template>
  <div class="register-page" :style="{ backgroundImage: `url(${bgImage})` }">
    <div class="register-wrapper">

      <!-- LEFT PANEL (FORM) -->
      <section class="register-left" aria-label="Registration form">
        <div class="left-inner">
          <h1 class="register-title">REGISTRATION</h1>

          <div class="brand-line">
            <div class="tsis">T-SIS</div>
          </div>

          <form class="register-form" @submit.prevent="handleRegister">
            <!-- Student number -->
            <div class="field">
              <input
                v-model.trim="form.student_number"
                type="text"
                placeholder="STUDENT NUMBER"
                autocomplete="username"
                :disabled="loading"
                required
              />
            </div>

            <!-- Birth date -->
            <div class="date-row">
              <select v-model="form.birth_month" class="date-input" :disabled="loading" required>
                <option value="" disabled>BIRTH MONTH</option>
                <option v-for="m in 12" :key="m" :value="String(m).padStart(2, '0')">
                  {{ String(m).padStart(2, '0') }}
                </option>
              </select>

              <input
                v-model.trim="form.birth_day"
                type="number"
                min="1"
                max="31"
                class="date-input"
                placeholder="BIRTH DAY"
                :disabled="loading"
                required
              />

              <input
                v-model.trim="form.birth_year"
                type="number"
                min="1900"
                :max="new Date().getFullYear()"
                class="date-input"
                placeholder="BIRTH YEAR"
                :disabled="loading"
                required
              />
            </div>

            <!-- Password -->
            <div class="field">
              <input
                v-model="form.password"
                type="password"
                placeholder="PASSWORD"
                autocomplete="new-password"
                :disabled="loading"
                required
              />
            </div>

            <!-- Confirm password -->
            <div class="field">
              <input
                v-model="form.password_confirmation"
                type="password"
                placeholder="RE-PASSWORD"
                autocomplete="new-password"
                :disabled="loading"
                required
              />
            </div>

            <button class="register-btn" type="submit" :disabled="loading">
              <span v-if="loading">CREATING…</span>
              <span v-else>REGISTER</span>
            </button>

            <transition name="fade">
              <p v-if="error" class="error">{{ error }}</p>
            </transition>

            <footer class="register-footer">©2025 T-SIS | ALL RIGHT RESERVED</footer>
          </form>
        </div>
      </section>

      <!-- RIGHT PANEL (WELCOME) -->
      <section class="register-right" aria-label="Welcome panel">
        <div class="school-header">
          <img :src="logoImage" alt="PUP Logo" class="school-logo" />
          <div class="school-name">
            POLYTECHNIC UNIVERSITY<br />
            OF THE PHILIPPINES
          </div>
        </div>

        <div class="right-content">
          <h2 class="welcome-title">HELLO, WELCOME!</h2>
          <div class="sub-cta-title">ALREADY HAVE AN ACCOUNT?</div>

          <router-link :to="{ name: 'login.student' }" class="signin-btn">
            SIGN IN
          </router-link>
        </div>
      </section>

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