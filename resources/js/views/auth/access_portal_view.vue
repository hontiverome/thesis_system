<template>
<div
  class="portal-page"
  :style="{ backgroundImage: `url(${bgImage})` }"
>
  <div class="glass-panel">
    <h1 class="portal-title">ACCESS PORTAL</h1>
    <p class="portal-subtitle">Select your user type to proceed.</p>

    <div class="options-grid">
      <button class="tile" type="button" @click="selectType('faculty')">
        <img
          :src="facultyIcon"
          alt="Faculty Icon"
          class="tile-icon-img"
        />
        <div class="tile-label">FACULTY</div>
      </button>

      <button class="tile" type="button" @click="selectType('student')">
        <img
          :src="studentIcon"
          alt="Student Icon"
          class="tile-icon-img"
        />
        <div class="tile-label">STUDENT</div>
      </button>
    </div>

    <button class="btn-back" type="button" @click="router.push('/')">
      Go Back
    </button>

    <div class="admin-section">
      <span class="text-muted">Administrator?</span>
      <button class="btn-link" type="button" @click="goToAdmin">
        Login here
      </button>
    </div>
  </div>
</div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import bgImage from '../../../assets/access_bg.jpg'
import facultyIcon from '../../../assets/faculty_icon.png'
import studentIcon from '../../../assets/student_icon.png'



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

  /* Background Image */
  --bg-image: url("/images/bg.jpg"); /* <-- change this */
  background-image: var(--bg-image);
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;

  padding: 2rem;
  position: relative;
}

/* Optional: darken background slightly for readability */
.portal-page::before {
  content: "";
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.25);
}

.glass-panel {
  position: relative;
  width: min(1100px, 95vw);
  padding: clamp(2rem, 4vw, 3.5rem);
  border-radius: 48px;

  background: rgba(255, 255, 255, 0.65);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);

  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
  text-align: center;
}

.portal-title {
  margin: 0;
  font-size: clamp(2.5rem, 5vw, 4.5rem);
  font-weight: 800;
  letter-spacing: 0.08em;
  color: #7b0d0d; /* maroon */
}

.portal-subtitle {
  margin: 0.75rem 0 2.5rem;
  font-size: clamp(1rem, 1.5vw, 1.25rem);
  letter-spacing: 0.35em;
  text-transform: lowercase;
  color: #1f2937;
}

.options-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(260px, 1fr));
  gap: clamp(1rem, 3vw, 2rem);
  justify-content: center;
  align-items: stretch;
  margin-bottom: 2.25rem;
}

.tile {
  position: relative;
  height: 360px;
  height: 360px;  
  width: 100%;

  border:none;
  border-radius: 36px;
  background: #7b0d0d;
  color: white;

  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-between;

  transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
}

.icon-area {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
}

.tile:hover {
  transform: translateY(-6px);
  box-shadow: 0 26px 50px rgba(123, 13, 13, 0.35);
  filter: brightness(1.05);
}

.tile:focus-visible {
  outline: 4px solid rgba(123, 13, 13, 0.35);
  outline-offset: 6px;
}
.tile-icon-img {
  max-width: 400px;
  max-height: 400px;
  width: 100%;
  height: 100%;
  object-fit: contain;
  filter: drop-shadow(0 8px 10px rgba(0, 0, 0, 0.35));
}
.tile-icon {
  font-size: 4.5rem;
  line-height: 1;
  margin-bottom: 0;
}
.tile:hover .tile-icon-img {
  transform: scale(1.05);
}

.tile-label {
  position: absolute;
  left: 0;
  right: 0;
  bottom: 22px;

  text-align: center;
  font-size: 1.8rem;
  letter-spacing: 0.35em;
  font-weight: 700;
  font-style: italic;
  text-align: center;
}

.btn-back {
  background: none;
  border: none;
  color: #111827;
  text-decoration: underline;
  cursor: pointer;
  font-size: 1rem;
  opacity: 0.8;
  margin-top: 0.25rem;
}

.btn-back:hover {
  opacity: 1;
}

.admin-section {
  margin-top: 1.25rem;
  font-size: 0.95rem;
  color: #374151;
}

.text-muted {
  opacity: 0.75;
}

.btn-link {
  background: none;
  border: none;
  color: #111827;
  cursor: pointer;
  font-weight: 700;
  margin-left: 0.35rem;
}

.btn-link:hover {
  text-decoration: underline;
}

/* Responsive: stack tiles on small screens */
@media (max-width: 700px) {
  .options-grid {
    grid-template-columns: 1fr;
  }
  .portal-subtitle {
    letter-spacing: 0.18em;
  }
}
</style>
