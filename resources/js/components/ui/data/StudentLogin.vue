<template>
  <div id="tsis-login-page-all-bold">
    <div class="login-container">
      <h1 class="title">STUDENT LOGIN</h1>
      
      <h2 class="subtitle">T-S I S</h2>

      <form @submit.prevent="handleLogin" class="login-form">
        <input
          type="text"
          v-model="studentNumber"
          placeholder="STUDENT NUMBER"
          class="input-field"
          required
        />

        <div class="dob-group">
          <input
            type="text"
            v-model="birthMonth"
            placeholder="BIRTH MONTH"
            class="dob-field" 
            maxlength="2"
            required
          />
          <input
            type="text"
            v-model="birthDay"
            placeholder="BIRTH DAY"
            class="dob-field"
            maxlength="2"
            required
          />
          <input
            type="text"
            v-model="birthYear"
            placeholder="BIRTH YEAR"
            class="dob-field"
            maxlength="4"
            required
          />
        </div>

        <input
          type="password"
          v-model="password"
          placeholder="PASSWORD"
          class="input-field"
          required
        />

        <a href="#" class="forgot-password">FORGOT PASSWORD?</a>

        <button type="submit" class="login-button">LOGIN</button>
      </form>

      <p class="footer-text">
        © 2025 T-SIS | ALL RIGHT RESERVED
      </p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TsisLoginAllBold',
  data() {
    return {
      studentNumber: '',
      birthMonth: '',
      birthDay: '',
      birthYear: '',
      password: '',
    };
  },
  methods: {
    handleLogin() {
      console.log('Attempting login with:', {
        studentNumber: this.studentNumber,
        dob: `${this.birthMonth}/${this.birthDay}/${this.birthYear}`,
        password: this.password,
      });
      alert('Login attempt initiated. Check console for data.');
    },
  },
};
</script>

<style scoped>
/* --- BASE LAYOUT (UNCHANGED) --- */
#tsis-login-page-all-bold {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
  box-sizing: border-box;
  color: #333;
}

.login-container {
  width: 100%;
  max-width: 450px;
  padding: 0;
  text-align: center;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.dob-group {
  display: flex;
  justify-content: space-between;
  gap: 10px;
}

.dob-field {
  flex: 1; 
  text-align: center;
}

/* --- FONT WEIGHT APPLICATION (ALL BOLD, UNCHANGED) --- */
.login-container, .input-field, .dob-field, .forgot-password, .footer-text {
  font-weight: bold;
}

/* --- INPUT FIELD SIZING (Height Consistency Confirmed) --- */
.input-field, .dob-field {
  padding: 15px 20px; 
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: #ffffff;
  color: #444;
  font-size: 1rem; /* Font size of *entered* text */
  text-transform: uppercase;
  font-family: serif;
  box-sizing: border-box; 
}

/* --- FIX: CONSISTENT PLACEHOLDER FONT SIZE --- */
/* Target all input placeholders together to ensure consistent sizing */
.input-field::placeholder, .dob-field::placeholder {
  color: #888;
  /* Setting all placeholder text to the smallest size required (0.75rem) */
  font-size: 0.75rem; 
  letter-spacing: 1px;
  font-weight: bold;
}

/* --- TYPOGRAPHY AND STYLING (REMAINDER UNCHANGED) --- */

.title {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 2.5rem;
  color: #8b0000;
  letter-spacing: 3px;
  margin-bottom: 5px;
}

.subtitle {
  font-family: serif;
  font-size: 1.8rem;
  color: #444444;
  letter-spacing: 5px;
  margin-bottom: 40px;
}

.forgot-password {
  color: #444444; 
  text-decoration: none;
  font-size: 0.9rem;
  margin-bottom: 5px;
  letter-spacing: 1px;
  font-family: serif;
}

.login-button {
  background-color: #8b0000; 
  color: #ffffff;
  border: none;
  padding: 15px 30px;
  border-radius: 5px;
  font-size: 1.2rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  cursor: pointer;
  font-family: Arial, Helvetica, sans-serif;
  transition: background-color 0.3s;
}

.login-button:hover {
  background-color: #a00000;
}

.footer-text {
  margin-top: 20px;
  font-size: 0.75rem;
  color: #444444; 
  letter-spacing: 1px;
  font-family: serif;
}
</style>