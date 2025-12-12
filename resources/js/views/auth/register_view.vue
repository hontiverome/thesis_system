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
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

import logoImage from '../../../assets/PUP_logo.png'
import bgImage from '../../../assets/access_bg.jpg'

const router = useRouter()
const loading = ref(false)
const error = ref('')

const form = reactive({
  student_number: '',
  birth_month: '',
  birth_day: '',
  birth_year: '',
  password: '',
  password_confirmation: ''
})

const handleRegister = async () => {
  loading.value = true
  error.value = ''

  try {
    await axios.post('/api/v1/auth/register', form)
    router.push({ name: 'login.student' })
  } catch (err) {
    if (err?.response?.data?.errors) {
      error.value = Object.values(err.response.data.errors).flat().join('\n')
    } else {
      error.value = err?.response?.data?.message || 'Registration failed.'
    }
  } finally {
    loading.value = false
  }
}
</script>


<style scoped>
.register-page{
  min-height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
  padding:28px;
  background-size:cover;
  background-position:center;
  background-repeat:no-repeat;
}

.register-page::before{
  content:"";
  position:fixed;
  inset:0;
  background:rgba(0,0,0,0.12);
  pointer-events:none;
}

/* Wrapper */
.register-wrapper{
  position:relative;
  width:min(1120px, 100%);
  min-height:620px;
  display:grid;
  grid-template-columns: 1fr 1.02fr; /* form left, welcome right */
  gap:32px;
  z-index:1;
}

/* LEFT (Glass form) */
.register-left{
  border-radius:52px;
  padding:44px 52px;
  background:rgba(255,255,255,0.62);
  backdrop-filter:blur(10px);
  -webkit-backdrop-filter:blur(10px);
  box-shadow:0 18px 60px rgba(0,0,0,0.18);
  display:flex;
  align-items:center;
}

.left-inner{ width:100%; text-align:center; }

.register-title{
  margin:0;
  font-size:38px;
  letter-spacing:0.22em;
  font-weight:700;
  color:#7b0a0a;
}

.brand-line{ margin-top:10px; margin-bottom:30px; }
.tsis{
  font-size:30px;
  letter-spacing:0.32em;
  font-weight:500;
  color:#111;
}

.register-form{
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

.date-row{
  display:grid;
  grid-template-columns: 1.3fr 1fr 1.2fr;
  gap:14px;
  margin:18px 0;
}

.date-input{
  width:100%;
  height:56px;
  border-radius:14px;
  border:none;
  outline:none;
  padding:0 18px;
  background:rgba(255,255,255,0.92);
  box-shadow:inset 0 0 0 1px rgba(0,0,0,0.08);
  font-size:12px;
  letter-spacing:0.18em;
  text-transform:uppercase;
  color:#111;
}

.date-input:focus{
  box-shadow:
    inset 0 0 0 2px rgba(123,10,10,0.35),
    0 0 0 3px rgba(123,10,10,0.12);
}

.register-btn{
  width:100%;
  height:58px;
  margin-top:18px;
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
.register-btn:hover:not(:disabled){ transform:translateY(-1px); }
.register-btn:disabled{ opacity:0.75; cursor:wait; }

.error{
  margin:14px 0 0;
  padding:10px 12px;
  border-radius:10px;
  background:rgba(255,240,240,0.85);
  border:1px solid rgba(200,0,0,0.2);
  color:#7b0a0a;
  font-size:13px;
  white-space:pre-wrap;
}

.register-footer{
  margin-top:18px;
  font-size:10px;
  letter-spacing:0.32em;
  color:rgba(0,0,0,0.55);
}

/* RIGHT (Red welcome) */
.register-right{
  position:relative;
  background:#7b0a0a;
  border-radius:52px;
  padding:44px 48px;
  color:#fff;
  overflow:hidden;
  display:flex;
  flex-direction:column;
}

.school-header{
  display:flex;
  align-items:center;
  gap:18px;
}

.school-logo{
  width:78px;
  height:78px;
  object-fit:contain;
}

.school-name{
  letter-spacing:0.14em;
  font-weight:700;
  font-size:14px;
  line-height:1.35;
  text-transform:uppercase;
  opacity:0.95;
}

.right-content{
  flex:1;
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  text-align:center;
  gap:18px;
}

.welcome-title{
  margin:0;
  font-size:52px;
  line-height:1;
  font-weight:500;
  letter-spacing:0.04em;
  text-transform:uppercase;
  font-family:"Comic Sans MS","Segoe Print","Bradley Hand",cursive;
  color:#fff;
}

.sub-cta-title{
  font-size:12px;
  letter-spacing:0.14em;
  font-weight:700;
  opacity:0.95;
}

.signin-btn{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  min-width:240px;
  height:56px;
  border-radius:12px;
  background:rgba(255,255,255,0.95);
  color:#2b2b2b;
  font-weight:800;
  letter-spacing:0.35em;
  text-decoration:none;
  box-shadow:0 10px 18px rgba(0,0,0,0.25);
  border:1px solid rgba(0,0,0,0.08);
}
.signin-btn:hover{ transform:translateY(-1px); }

.fade-enter-active,.fade-leave-active{ transition:opacity .25s ease; }
.fade-enter-from,.fade-leave-to{ opacity:0; }

@media (max-width:980px){
  .register-wrapper{ grid-template-columns:1fr; gap:18px; min-height:auto; }
  .register-left,.register-right{ border-radius:34px; }
  .welcome-title{ font-size:44px; }
}
</style>
