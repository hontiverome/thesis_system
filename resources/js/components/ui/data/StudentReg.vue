<template>
  <div id="tsis-register-page-all-bold">
    <div class="register-container">
      <h1 class="title">REGISTRATION</h1>
      
      <h2 class="subtitle">T-S I S</h2>

      <form @submit.prevent="handleRegister" class="register-form">
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
        
        <input
          type="password"
          v-model="rePassword"
          placeholder="RE-PASSWORD"
          class="input-field"
          required
        />

        <button type="submit" class="register-button">REGISTER</button>
      </form>

      <p class="footer-text">
        © 2025 T-SIS | ALL RIGHT RESERVED
      </p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TsisRegisterAllBold',
  data() {
    return {
      studentNumber: '',
      birthMonth: '',
      birthDay: '',
      birthYear: '',
      password: '',
      rePassword: '', 
    };
  },
  methods: {
    handleRegister() {
      console.log('Attempting register with:', {
        studentNumber: this.studentNumber,
        dob: `${this.birthMonth}/${this.birthDay}/${this.birthYear}`,
        password: this.password,
        rePassword: this.rePassword,
      });
      alert('Register attempt initiated. Check console for data.');
    },
  },
};
</script>

<style scoped>
/* --- BASE LAYOUT (UNCHANGED) --- */
#tsis-register-page-all-bold {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
  box-sizing: border-box;
  color: #333;
}

.register-container {
  width: 100%;
  max-width: 450px;
  padding: 0;
  text-align: center;
}

.register-form {
  display: flex;
  gap: 20px;
  flex-direction: column;
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
.register-container, .input-field, .dob-field, .footer-text {
  font-weight: bold;
}

/* The .forgot-password rule is no longer necessary but is harmless if left */

/* --- INPUT FIELD SIZING (RETAINS ORIGINAL SIZE) --- */
.input-field, .dob-field {
  padding: 15px 20px; 
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: #ffffff;
  color: #444;
  font-size: 1rem; 
  text-transform: uppercase;
  font-family: serif;
  box-sizing: border-box; 
}

/* --- PLACEHOLDER FONT SIZE (UNCHANGED) --- */
.input-field::placeholder, .dob-field::placeholder {
  color: #888;
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
  /* This rule is now orphaned but left for completeness */
  color: #444444; 
  text-decoration: none;
  font-size: 0.9rem;
  margin-bottom: 5px;
  letter-spacing: 1px;
  font-family: serif;
}

.register-button {
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

.register-button:hover {
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