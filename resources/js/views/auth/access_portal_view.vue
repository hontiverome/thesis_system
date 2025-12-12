<template>
  <div class="portal-page">
    <div class="glass-panel">
      <h1 class="portal-title">ACCESS PORTAL</h1>
      <p class="portal-subtitle">Select your user type to proceed.</p>

      <div class="options-grid">
        <button class="tile" type="button" @click="selectType('faculty')">
          <div class="tile-icon">👨‍🏫</div>
          <div class="tile-label">FACULTY</div>
        </button>

        <button class="tile" type="button" @click="selectType('student')">
          <div class="tile-icon">👨‍🎓</div>
          <div class="tile-label">STUDENT</div>
        </button>
      </div>

      <button class="btn-back" type="button" @click="router.push('/')">Go Back</button>

      <div class="admin-section">
        <span class="text-muted">Administrator?</span>
        <button class="btn-link" type="button" @click="goToAdmin">Login here</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from "vue-router";

const router = useRouter();

const selectType = (type) => {
  if (type === "student") router.push({ name: "login.student" });
  if (type === "faculty") router.push({ name: "login.faculty" });
};

const goToAdmin = () => {
  router.push({ name: "login" });
};
</script>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

// Navigate to specific portals
const selectType = (type) => {
  if (type === 'student') {
    router.push({ name: 'login.student' });
  } else if (type === 'faculty') {
    router.push({ name: 'login.faculty' });
  }
};

// Navigate to the main/admin login (Email & Password)
const goToAdmin = () => {
  router.push({ name: 'login' });
};
</script>

<style scoped>
.portal-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--bg-color, #f8f9fa);
  padding: 1rem;
}

.portal-container {
  max-width: 800px;
  width: 100%;
  text-align: center;
}

.options-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 2rem;
  margin: 3rem 0;
}

.option-card {
  background: white;
  padding: 2.5rem;
  border-radius: 16px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  cursor: pointer;
  transition: all 0.3s ease;
  border: 2px solid transparent;
}

.option-card:hover {
  transform: translateY(-5px);
  border-color: var(--primary-color, #4F46E5);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.icon-box {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.btn-back {
  background: none;
  border: none;
  color: #666;
  text-decoration: underline;
  cursor: pointer;
  font-size: 1rem;
  margin-bottom: 1.5rem; /* Add space below Go Back */
}

/* New Styles for Admin Section */
.admin-section {
  margin-top: 1rem;
  font-size: 0.9rem;
  color: #888;
}

.btn-link {
  background: none;
  border: none;
  color: var(--primary-color, #4F46E5);
  cursor: pointer;
  font-weight: 600;
  text-decoration: none;
  padding: 0;
  margin-left: 0.25rem;
}

.btn-link:hover {
  text-decoration: underline;
}
</style>