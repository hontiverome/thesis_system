<template>
  <div class="login-page" :style="{ backgroundImage: `url(${bgImage})` }">
    <div class="login-wrapper auth-stage" :class="{ switch: isRegistering, entering: isEntering }">
      <!-- LEFT PANEL -->
      <section class="login-left" aria-label="Welcome panel">
        <!-- Back icon (top-left) -->
        <router-link :to="{ name: 'access-portal' }" class="back-icon" aria-label="Back to portal">
          ‹
        </router-link>

        <div class="school-header">
          <div class="school-name">
            POLYTECHNIC UNIVERSITY
            OF THE PHILIPPINES
          </div>

          <img :src="logoImage" alt="PUP Logo" class="school-logo" />
        </div>

        <div class="left-content student-left">
          <h2 class="welcome-title">WELCOME BACK!</h2>

          <div class="sub-cta">
            <div class="sub-cta-title">DON'T HAVE AN ACCOUNT?</div>
          </div>

           <button class="register-btn" @click="goToRegister">
            REGISTER
          </button>

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
            <!-- First Row: First Name and Surname -->
            <div class="form-row">
              <div class="field">
                <input
                  v-model.trim="form.firstName"
                  type="text"
                  placeholder="FIRST NAME"
                  autocomplete="given-name"
                  :disabled="loading"
                  required
                />
              </div>
              <div class="field">
                <input
                  v-model.trim="form.lastName"
                  type="text"
                  placeholder="SURNAME"
                  autocomplete="family-name"
                  :disabled="loading"
                  required
                />
              </div>
            </div>

            <!-- STUDENT NUMBER -->
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
                :type="showPassword ? 'text' : 'password'"
                placeholder="PASSWORD"
                autocomplete="current-password"
                :disabled="loading"
                required
              />
              <button type="button" class="toggle-password" @click="showPassword = !showPassword" tabindex="-1">
                <IconifyIcon :icon="showPassword ? 'mdi:eye-off' : 'mdi:eye'" class="password-icon" />
              </button>
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
      <!-- RED SWEEP OVERLAY -->
      <div class="red-sweep"></div>

    </div>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'
import { Icon as IconifyIcon } from '@iconify/vue'

import logoImage from '../../../assets/PUP_logo.png'
import bgImage from '../../../assets/access_bg.jpg'

const router = useRouter()
const { login } = useAuth()

const loading = ref(false)
const error = ref('')
const showPassword = ref(false)

const form = reactive({
  firstName: '',
  lastName: '',
  student_number: '',
  birth_month: '',
  birth_day: '',
  birth_year: '',
  password: ''
})



const isRegistering = ref(false)

const goToRegister = () => {
  isEntering.value = false
  isRegistering.value = true

  // wait for animation before routing
  setTimeout(() => {
    router.push({ name: 'register' })
  }, 900)
}

const isEntering = ref(false)

onMounted(() => {
  requestAnimationFrame(() => {
    isEntering.value = true
  })
})



const handleLogin = async () => {
  loading.value = true
  error.value = ''

  try {
    await login({ ...form })
    router.push('/student-dashboard') // change if your student dashboard route differs
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

/* base animation */
.auth-stage {
  position: relative;
  overflow: hidden;
}

/* smooth movement */
/* INITIAL (before mount) — NO transition */
.login-left,
.login-right {
  transform: translateX(0);
}

/* When NOT yet entered */
.auth-stage:not(.entering) .login-left {
  transform: translateX(-100%);
}

.auth-stage:not(.entering) .login-right {
  transform: translateX(100%);
}

.red-sweep {
  transition: transform 0.9s cubic-bezier(.77,0,.18,1);
}


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
  width: min(2000px, 100%);
  min-height: 800px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 50px;
  z-index: 1;
}

/* LEFT */
.login-left {
  position: relative;
  background: #7b0a0a;
  border-radius: 0 52px 52px 0;
  padding: 40px 40px;
  color: #fff;
  overflow: hidden;
  display: flex;
  flex-direction: column;
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
  padding:20px 30px;
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
  flex: 1;
  justify-content: center;
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
  left: 40px;
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
  border-radius: 52px 0 0 52px;
  padding: 40px 40px;
  background: rgba(255,255,255,0.62);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  box-shadow: 0 18px 60px rgba(0,0,0,0.18);
  display: flex;
  align-items: center;
}

.red-sweep {
  position: absolute;
  top: 0;
  bottom: 0;
  right: -50%;
  width: 50%;
  background: #7b0a0a;
  border-radius: 0 52px 52px 0;
  z-index: 3;
  pointer-events: none;
}

/* WHEN SWITCHING TO REGISTER */
.auth-stage.switch .login-right {
  transform: translateX(100%);
}

.auth-stage.switch .login-left {
  transform: translateX(-100%);
}

.auth-stage.switch .red-sweep {
  transform: translateX(-100%);
}

.auth-stage.switch .register-left,
.auth-stage.switch .register-right {
  transform: translateX(100%);
}

.auth-stage.switch .red-sweep {
  transform: translateX(100%);
}
/* EXIT (LOGIN → REGISTER) — animate OUT */
.auth-stage.switch .login-left,
.auth-stage.switch .login-right,
.auth-stage.switch .red-sweep {
  transition: transform 0.9s cubic-bezier(.77,0,.18,1);
}


/* INITIAL POSITIONS FOR ENTRANCE */
.auth-stage.entering .login-left {
  transform: translateX(-100%); /* left panel off-screen left */
}

.auth-stage.entering .login-right {
  transform: translateX(100%); /* right panel off-screen right */
}

/* SLIDE IN TRANSITION */
.auth-stage.entering .login-left,
.auth-stage.entering .login-right {
  transition: transform 0.9s cubic-bezier(.77,0,.18,1);
  transform: translateX(0);
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

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}

.field { position: relative; margin: 15px 0; }

.field input {
  width: 100%;
  height: 56px;
  border-radius: 14px;
  border: none;
  outline: none;
  padding: 0 60px 0 18px;
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

.toggle-password {
  position: absolute;
  right: 16px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: rgba(0,0,0,0.5);
  cursor: pointer;
  display: flex;
  align-items: center;
}

.password-icon {
  font-size: 20px;
}
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
  justify-content: center;
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
