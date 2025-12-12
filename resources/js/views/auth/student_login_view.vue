<template>
  <div class="login-page" :style="{ backgroundImage: `url(${bgImage})` }">
    <div class="login-wrapper">
      <!-- LEFT PANEL -->
      <section class="login-left" aria-label="Welcome panel">
        <!-- Back icon (top-left) -->
        <router-link :to="{ name: 'access-portal' }" class="back-icon" aria-label="Back to portal">
          ‹
        </router-link>

        <div class="school-header">
          <div class="school-name">
            POLYTECHNIC UNIVERSITY<br />
            OF THE PHILIPPINES
          </div>

          <img :src="logoImage" alt="PUP Logo" class="school-logo" />
        </div>

        <div class="left-content student-left">
          <h2 class="welcome-title">WELCOME BACK!</h2>

          <div class="sub-cta">
            <div class="sub-cta-title">DON'T HAVE AN ACCOUNT?</div>
          </div>

          <router-link :to="{ name: 'register' }" class="register-btn">
            REGISTER
          </router-link>

          <!-- Bottom "Back to Portal" -->
          <div class="left-bottom">
            <router-link :to="{ name: 'access-portal' }" class="back-portal">
              BACK TO PORTAL
            </router-link>
          </div>
        </div>
      </section>

      <!-- RIGHT PANEL -->
      <section class="login-right" aria-label="Login panel">
        <div class="right-inner">
          <h1 class="login-title">STUDENT LOGIN</h1>

          <div class="brand-line">
            <div class="tsis">T-SIS</div>
          </div>

          <form class="login-form" @submit.prevent="handleLogin">
            <!-- STUDENT NUMBER -->
            <div class="field">
              <input
                v-model.trim="form.SchoolID"
                type="text"
                placeholder="STUDENT NUMBER"
                autocomplete="username"
                :disabled="loading"
                required
              />
            </div>

            <!-- BIRTH DATE (MONTH / DAY / YEAR) -->
            <div class="date-row">
              <select
                v-model="form.birth_month"
                class="date-input"
                :disabled="loading"
                required
              >
                <option value="" disabled> BIRTH MONTH </option>
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

            <!-- PASSWORD -->
            <div class="field">
              <input
                v-model="form.password"
                type="password"
                placeholder="PASSWORD"
                autocomplete="current-password"
                :disabled="loading"
                required
              />
            </div>

            <div class="row">
              <a href="#" class="forgot" @click.prevent>
                FORGOT PASSWORD?
              </a>
            </div>

            <button class="login-btn" type="submit" :disabled="loading">
              <span v-if="loading">LOGGING IN…</span>
              <span v-else>LOGIN</span>
            </button>

            <transition name="fade">
              <p v-if="error" class="error">{{ error }}</p>
            </transition>
          </form>

          <footer class="login-footer">2025 T-SIS | ALL RIGHT RESERVED</footer>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'

import logoImage from '../../../assets/PUP_logo.png'
import bgImage from '../../../assets/access_bg.jpg'

const router = useRouter()
const { studentLogin } = useAuth()

const loading = ref(false)
const error = ref('')

const form = reactive({
  SchoolID: '',
  birth_month: '',
  birth_day: '',
  birth_year: '',
  password: ''
})

const handleLogin = async () => {
  loading.value = true
  error.value = ''

  try {
    // Convert birth month and day to integers for API
    const loginData = {
      ...form,
      birth_month: parseInt(form.birth_month),
      birth_day: parseInt(form.birth_day),
      birth_year: parseInt(form.birth_year)
    }
    
    const response = await studentLogin(loginData)
    
    // Redirect to home for now
    router.push('/home')
  } catch (err) {
    error.value =
      err?.response?.data?.message ||
      err?.message ||
      'Login failed. Please check your credentials.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.login-page {
  width: 100vw;
  height: 100wh;
  margin: 0;
  padding: 0;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 28px;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}

.login-page::before {
  content: "";
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.12);
  pointer-events: none;
}

.login-wrapper {
  position: relative;
  width: min(1120px, 100%);
  min-height: 620px;
  display: grid;
  grid-template-columns: 1.02fr 1fr;
  gap: 32px;
  z-index: 1;
}

/* LEFT */
.login-left {
  position: relative;
  background: #7b0a0a;
  border-radius: 52px;
  padding: 44px 48px;
  color: #fff;
  overflow: hidden;
}

.back-icon {
  position: absolute;
  top: 24px;
  left: 22px;
  width: 34px;
  height: 34px;
  display: grid;
  place-items: center;
  text-decoration: none;
  color: rgba(255,255,255,0.9);
  font-size: 34px;
  line-height: 1;
}

.school-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 18px;
}

.school-name {
  letter-spacing: 0.14em;
  font-weight: 700;
  font-size: 14px;
  line-height: 1.35;
  text-transform: uppercase;
  opacity: 0.95;
}

.school-logo {
  width: 68px;
  height: 68px;
  object-fit: contain;
}

.left-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}


.student-left {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-emphasis: center;
}

.welcome-title {
  margin: 0 0 18px;
  font-size: 56px;
  line-height: 1;
  font-weight: 500;
  letter-spacing: 0.04em;
  text-transform: uppercase;
  color: #fff;
  font-family: "Comic Sans MS","Segoe Print","Bradley Hand",cursive;
}

.sub-cta {
  margin-top: 12px;
  margin-bottom: 22px;
}

.sub-cta-title {
  font-size: 12px;
  letter-spacing: 0.14em;
  font-weight: 700;
  opacity: 0.95;
}

.register-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 220px;
  height: 56px;
  border-radius: 12px;
  background: rgba(255,255,255,0.95);
  color: #2b2b2b;
  font-weight: 700;
  letter-spacing: 0.35em;
  text-decoration: none;
  box-shadow: 0 10px 18px rgba(0,0,0,0.25);
  border: 1px solid rgba(0,0,0,0.08);
}

.register-btn:hover {
  transform: translateY(-1px);
}

.left-bottom {
  position: absolute;
  bottom: 28px;
  left: 48px;
}

.back-portal {
  font-size: 11px;
  letter-spacing: 0.32em;
  color: rgba(255,255,255,0.8);
  text-decoration: none;
}
.back-portal:hover { text-decoration: underline; }

/* RIGHT */
.login-right {
  border-radius: 52px;
  padding: 44px 52px;
  background: rgba(255,255,255,0.62);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  box-shadow: 0 18px 60px rgba(0,0,0,0.18);
  display: flex;
  align-items: center;
}

.right-inner { width: 100%; text-align: center; }

.login-title {
  margin: 0;
  font-size: 42px;
  letter-spacing: 0.18em;
  font-weight: 700;
  color: #7b0a0a;
}

.brand-line { margin-top: 10px; margin-bottom: 36px; }

.tsis {
  font-size: 34px;
  letter-spacing: 0.32em;
  font-weight: 500;
  color: #111;
}

.login-form {
  width: 100%;
  max-width: 520px;
  margin: 0 auto;
}

.field { position: relative; margin: 18px 0; }

.field input {
  width: 100%;
  height: 56px;
  border-radius: 14px;
  border: none;
  outline: none;
  padding: 0 18px;
  background: rgba(255,255,255,0.92);
  box-shadow: inset 0 0 0 1px rgba(0,0,0,0.08);
  font-size: 13px;
  letter-spacing: 0.24em;
  text-transform: uppercase;
  color: #111;
}
.field input::placeholder { color: rgba(0,0,0,0.45); }
.field input:focus {
  box-shadow: inset 0 0 0 2px rgba(123,10,10,0.35),
              0 0 0 3px rgba(123,10,10,0.12);
}
.field input:disabled { opacity: 0.75; cursor: not-allowed; }

/* DATE GRID */
.date-row {
  display: grid;
  grid-template-columns: 1.3fr 1fr 1.2fr;
  gap: 14px;
  margin: 18px 0;
}

.date-input {
  width: 100%;
  height: 56px;
  border-radius: 14px;
  border: none;
  outline: none;
  padding: 0 18px;
  background: rgba(255,255,255,0.92);
  box-shadow: inset 0 0 0 1px rgba(0,0,0,0.08);
  font-size: 12px;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: #111;
}

.date-input:focus {
  box-shadow: inset 0 0 0 2px rgba(123,10,10,0.35),
              0 0 0 3px rgba(123,10,10,0.12);
}

.date-input:disabled { opacity: 0.75; cursor: not-allowed; }

.row {
  display: flex;
  justify-content: flex-end;
  margin-top: 8px;
}

.forgot {
  font-size: 11px;
  letter-spacing: 0.22em;
  color: #222;
  text-decoration: none;
}
.forgot:hover { text-decoration: underline; }

.login-btn {
  width: 100%;
  height: 58px;
  margin-top: 20px;
  border: none;
  border-radius: 14px;
  background: #7b0a0a;
  color: #fff;
  font-size: 18px;
  letter-spacing: 0.38em;
  font-weight: 700;
  cursor: pointer;
  transition: transform .08s ease, opacity .2s ease;
}
.login-btn:hover:not(:disabled) { transform: translateY(-1px); }
.login-btn:disabled { opacity: 0.75; cursor: wait; }

.error {
  margin: 14px 0 0;
  padding: 10px 12px;
  border-radius: 10px;
  background: rgba(255,240,240,0.85);
  border: 1px solid rgba(200,0,0,0.2);
  color: #7b0a0a;
  font-size: 13px;
}

.login-footer {
  margin-top: 26px;
  font-size: 10px;
  letter-spacing: 0.32em;
  color: rgba(0,0,0,0.55);
}

.fade-enter-active, .fade-leave-active { transition: opacity .25s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

@media (max-width: 980px) {
  .login-wrapper { grid-template-columns: 1fr; gap: 18px; min-height: auto; }
  .login-left, .login-right { border-radius: 34px; }
  .left-content { margin-top: 36px; }
  .welcome-title { font-size: 44px; }
}

@media (max-width: 520px) {
  .login-page { padding: 16px; }
  .login-left { padding: 28px 26px; }
  .login-right { padding: 28px 26px; }
  .login-title { font-size: 34px; }
  .tsis { font-size: 28px; }
  .register-btn { min-width: 200px; }
}
</style>
