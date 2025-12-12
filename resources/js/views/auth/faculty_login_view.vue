<template>
  <div class="login-page" :style="{ backgroundImage: `url(${bgImage})` }">
    <div class="login-wrapper">

      <!-- LEFT PANEL -->
      <section class="login-left" aria-label="Welcome panel">
        <div class="school-header">
          <div class="school-name">
            POLYTECHNIC UNIVERSITY<br />
            OF THE PHILIPPINES
          </div>

          <!-- optional logo (won't crash if you remove it) -->
          <img v-if="logoImage" :src="logoImage" alt="Logo" class="school-logo" />
        </div>

        <div class="left-content">
          <h2 class="welcome-title">GREETINGS, FACULTY!</h2>
          <p class="welcome-text">
            Thank you for your dedication.<br />
            Continue to your dashboard.
          </p>
        </div>
      </section>

      <!-- RIGHT PANEL -->
      <section class="login-right" aria-label="Login panel">
        <div class="right-inner">
          <h1 class="login-title">FACULTY LOGIN</h1>

          <div class="brand-line">
            <div class="tsis">T-SIS</div>
            <div class="adviser">ADVISER</div>
          </div>

          <form class="login-form" @submit.prevent="handleLogin">
            <div class="field field-select">
              <input
                v-model.trim="form.faculty_id"
                type="text"
                placeholder="FACULTY ID"
                autocomplete="username"
                :disabled="loading"
                required
              />
              <span class="chev" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="18" height="18">
                  <path
                    d="M6 9l6 6 6-6"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>
              </span>
            </div>

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
              <!-- Use your existing portal route name -->
              <router-link :to="{ name: 'access-portal' }" class="forgot">
                BACK TO PORTAL
              </router-link>
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
import bgImage from '../../../assets/access_bg.jpg'

const router = useRouter()
const { login } = useAuth()

const loading = ref(false)
const error = ref('')

const form = reactive({
  faculty_id: '',
  password: ''
})

const handleLogin = async () => {
  loading.value = true
  error.value = ''

  try {
    await login({ ...form })
    router.push('/dashboard')
  } catch (err) {
    error.value =
      err?.response?.data?.message ||
      err?.message ||
      'Login failed. Please check your Faculty ID and password.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.login-page{
  min-height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
  padding:28px;
  background-size:cover;
  background-position:center;
  background-repeat:no-repeat;
}

.login-page::before{
  content:"";
  position:fixed;
  inset:0;
  background:rgba(0,0,0,0.12);
  pointer-events:none;
}

.login-wrapper{
  position:relative;
  width:min(1120px, 100%);
  min-height:620px;
  display:grid;
  grid-template-columns: 1.02fr 1fr;
  gap:32px;
  z-index:1;
}

.login-left{
  background:#7b0a0a;
  border-radius:52px;
  padding:44px 48px;
  color:#fff;
  overflow:hidden;
}

.school-header{
  display:flex;
  align-items:flex-start;
  justify-content:space-between;
  gap:18px;
}

.school-name{
  letter-spacing:0.14em;
  font-weight:700;
  font-size:14px;
  line-height:1.35;
  text-transform:uppercase;
  opacity:0.95;
}

.school-logo{
  width:64px;
  height:64px;
  object-fit:contain;
}

.left-content{ margin-top:78px; }

.welcome-title{
  margin:0 0 18px;
  font-size:56px;
  line-height:1;
  font-weight:500;
  letter-spacing:0.04em;
  text-transform:uppercase;
  font-family:"Comic Sans MS","Segoe Print","Bradley Hand",cursive;
}

.welcome-text{
  margin:0;
  font-size:16px;
  line-height:1.45;
  opacity:0.92;
}

.login-right{
  border-radius:52px;
  padding:44px 52px;
  background:rgba(255,255,255,0.62);
  backdrop-filter:blur(10px);
  -webkit-backdrop-filter:blur(10px);
  box-shadow:0 18px 60px rgba(0,0,0,0.18);
  display:flex;
  align-items:center;
}

.right-inner{ width:100%; text-align:center; }

.login-title{
  margin:0;
  font-size:42px;
  letter-spacing:0.18em;
  font-weight:700;
  color:#7b0a0a;
}

.brand-line{ margin-top:10px; margin-bottom:36px; }

.tsis{
  font-size:34px;
  letter-spacing:0.32em;
  font-weight:500;
  color:#111;
}
.adviser{
  margin-top:4px;
  font-size:11px;
  letter-spacing:0.36em;
  font-weight:700;
  color:#2b82c5;
}

.login-form{
  width:100%;
  max-width:520px;
  margin:0 auto;
}

.field{ position:relative; margin:18px 0; }

.field input{
  width:100%;
  height:56px;
  border-radius:14px;
  border:none;
  outline:none;
  padding:0 18px;
  background:rgba(255,255,255,0.92);
  box-shadow:inset 0 0 0 1px rgba(0,0,0,0.08);
  font-size:13px;
  letter-spacing:0.24em;
  text-transform:uppercase;
  color:#111;
}

.field input::placeholder{ color:rgba(0,0,0,0.45); }

.field input:focus{
  box-shadow:
    inset 0 0 0 2px rgba(123,10,10,0.35),
    0 0 0 3px rgba(123,10,10,0.12);
}

.field input:disabled{ opacity:0.75; cursor:not-allowed; }

.field-select input{ padding-right:46px; }

.chev{
  position:absolute;
  right:16px;
  top:50%;
  transform:translateY(-50%);
  color:rgba(0,0,0,0.55);
}

.row{
  display:flex;
  justify-content:flex-end;
  margin-top:8px;
}

.forgot{
  font-size:11px;
  letter-spacing:0.22em;
  color:#222;
  text-decoration:none;
}
.forgot:hover{ text-decoration:underline; }

.login-btn{
  width:100%;
  height:58px;
  margin-top:20px;
  border:none;
  border-radius:14px;
  background:#7b0a0a;
  color:#fff;
  font-size:18px;
  letter-spacing:0.38em;
  font-weight:700;
  cursor:pointer;
  transition:transform .08s ease, opacity .2s ease;
}
.login-btn:hover:not(:disabled){ transform:translateY(-1px); }
.login-btn:disabled{ opacity:0.75; cursor:wait; }

.error{
  margin:14px 0 0;
  padding:10px 12px;
  border-radius:10px;
  background:rgba(255,240,240,0.85);
  border:1px solid rgba(200,0,0,0.2);
  color:#7b0a0a;
  font-size:13px;
}

.login-footer{
  margin-top:26px;
  font-size:10px;
  letter-spacing:0.32em;
  color:rgba(0,0,0,0.55);
}

.fade-enter-active,.fade-leave-active{ transition:opacity .25s ease; }
.fade-enter-from,.fade-leave-to{ opacity:0; }

@media (max-width:980px){
  .login-wrapper{ grid-template-columns:1fr; gap:18px; min-height:auto; }
  .login-left,.login-right{ border-radius:34px; }
  .left-content{ margin-top:36px; }
  .welcome-title{ font-size:44px; }
}

@media (max-width:520px){
  .login-page{ padding:16px; }
  .login-left{ padding:28px 26px; }
  .login-right{ padding:28px 26px; }
  .login-title{ font-size:34px; }
  .tsis{ font-size:28px; }
}
</style>
