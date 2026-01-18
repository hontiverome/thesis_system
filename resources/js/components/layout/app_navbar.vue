<template>
  <header class="pup-navbar">
    <div class="navbar-container">
<<<<<<< Updated upstream
      
      <div class="navbar-branding">
        <div class="logo-image-container">
          <img 
            :src="logoImage" 
            alt="Polytechnic University of the Philippines Logo" 
            class="pup-logo-img" 
          />
=======

      <!-- Branding -->
      <div class="navbar-branding">
        <div class="logo-image-container">
          <img
            src="assets/PUP_logo.png" 
            alt="Polytechnic University of the Philippines Logo"
            class="pup-logo-img"
          /> <!-- will vary depends on the actual storage of the src (routing job) -->
>>>>>>> Stashed changes
        </div>
        <div class="university-info">
          <h1 class="uni-name">Polytechnic University of the Philippines</h1>
          <span class="system-name">T-SIS</span>
        </div>
      </div>

<<<<<<< Updated upstream
      <div class="navbar-actions">
        
        <nav class="top-links">
          <a href="#" class="header-link">HOMEPAGE</a>
          <span class="separator">|</span>
          <a href="#" class="header-link">COURSE</a>
        </nav>

        <div class="user-menu" @click.stop="toggleUserMenu">
          <button class="user-button" aria-label="User menu" :aria-expanded="isUserMenuOpen">
            <span class="user-label-text">USER</span>
            
=======
      <!-- Actions -->
      <div class="navbar-actions">
        <nav class="top-links">
          <router-link to="/" class="header-link">HOMEPAGE</router-link>
          <span class="separator">|</span>
          <router-link to="/courses" class="header-link">COURSE</router-link>
        </nav>

        <!-- User Menu -->
        <div class="user-menu" @click.stop="toggleUserMenu">
          <button class="user-button" :aria-expanded="isUserMenuOpen">
            <span class="user-label-text">USER</span>

>>>>>>> Stashed changes
            <div class="user-avatar-container">
              <div class="user-avatar-initials">U</div>
            </div>
<<<<<<< Updated upstream
            <IconifyIcon class="icon-chevron" :class="{ 'rotate-180': isUserMenuOpen }" icon="mdi:chevron-down" />
          </button>
          
          <div v-if="isUserMenuOpen" class="user-dropdown active">
            <div class="user-dropdown-header">
              <div class="user-info">
                <div class="user-name">{{ userStore.user?.firstName }} {{ userStore.user?.lastName || '' }}</div>
                <div class="user-email">{{ userStore.user?.email || '' }}</div>
=======

            <IconifyIcon
              class="icon-chevron"
              :class="{ 'rotate-180': isUserMenuOpen }"
              icon="mdi:chevron-down"
            />
          </button>

          <!-- Dropdown -->
          <div v-if="isUserMenuOpen" class="user-dropdown">
            <div class="user-dropdown-header">
              <div class="user-header-content">
                <div class="dropdown-user-avatar">
                  <span class="dropdown-avatar-initials">U</span>
                <!--<img src="/user-placeholder.png" alt="User Icon" /> for the actual source (stored)-->
                </div>

                <div class="user-info">
                  <div class="user-name">Phainon Khaslana</div>
                  <div class="user-email">sirtaposnapo@iskolarngbayan.pup.edu.ph</div>
                </div>
>>>>>>> Stashed changes
              </div>
            </div>

            <ul class="user-dropdown-menu">
              <li class="dropdown-row">
                <router-link to="/profile" class="dropdown-link" @click="closeUserMenu">
                  <span>Profile</span>
                  <span class="arrow">›</span>
                </router-link>
              </li>

              <li class="dropdown-row">
                <router-link to="/notif" class="dropdown-link" @click="closeUserMenu">
                  <span>Notification</span>
                  <span class="arrow">›</span>
                </router-link>
              </li>

              <li class="dropdown-row">
                <router-link to="/help" class="dropdown-link" @click="closeUserMenu">
                  <span>Help & Support</span>
                  <span class="arrow">›</span>
                </router-link>
              </li>

              <li class="dropdown-row">
                <router-link to="/settings" class="dropdown-link" @click="closeUserMenu">
                  <span>Settings</span>
                  <span class="arrow">›</span>
                </router-link>
              </li>

              <li class="dropdown-row logout">
                <button class="dropdown-link" @click="handleLogout">
                  <span>Logout</span>
                  <span class="arrow">›</span>
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>

    </div>
  </header>
</template>

<script setup>
<<<<<<< Updated upstream
import { onMounted, ref, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { useLayoutStore } from '@/stores/layout.js';
import { useUserStore } from '@/stores/user.js';
import { loadIcons } from '@iconify/vue';
import { useAuth } from '@/composables/useAuth';

// ⚡️ FIX: Explicitly import the image using a relative path.
// If this still fails, double-check that 'resources/js/assets/PUP_logo.png' exists.
import logoImage from '../../../assets/PUP_logo.png';

=======
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { loadIcons } from '@iconify/vue'

const router = useRouter()
const isUserMenuOpen = ref(false)

const toggleUserMenu = () => {
  isUserMenuOpen.value = !isUserMenuOpen.value
}

const closeUserMenu = () => {
  isUserMenuOpen.value = false
}

const handleLogout = () => {
  closeUserMenu()
  router.push({ name: 'login' })
}

>>>>>>> Stashed changes
const handleClickOutside = (event) => {
  const menu = document.querySelector('.user-menu')
  if (menu && !menu.contains(event.target)) {
    closeUserMenu()
  }
<<<<<<< Updated upstream
};

const router = useRouter();
const layoutStore = useLayoutStore();
const userStore = useUserStore();

// ⚡️ FIX: Destructure logout from useAuth
const { logout } = useAuth();

const isUserMenuOpen = ref(false);

const toggleUserMenu = () => {
  isUserMenuOpen.value = !isUserMenuOpen.value;
};

const closeUserMenu = () => {
  isUserMenuOpen.value = false;
};

const handleLogout = async () => {
  try {
    // ⚡️ FIX: Use the composable logout logic
    await logout();
    
    // Cleanup UI state
    closeUserMenu();
    
    // Force redirect to the Student Login page
    router.push('/login/student');
  } catch (error) {
    console.error('Logout failed:', error);
    // Force redirect anyway
    router.push('/login/student');
  }
};

onMounted(() => {
  loadIcons([
    'mdi:account', 'mdi:chevron-down', 'mdi:cog', 'mdi:logout'
  ]);
  document.addEventListener('click', handleClickOutside);
});
=======
}

onMounted(() => {
  loadIcons(['mdi:chevron-down'])
  document.addEventListener('click', handleClickOutside)
})
>>>>>>> Stashed changes

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
<<<<<<< Updated upstream
/* Import the Sorts Mill Goudy font */
@import url('https://fonts.googleapis.com/css2?family=Sorts+Mill+Goudy&display=swap');

/* PUP COLOR PALETTE */
.pup-navbar {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 1000;
  margin: 0;
  
  background-color: #800000;
  color: white;
  height: 80px; 
  box-shadow: 0 2px 4px rgba(0,0,0,0.2);
  display: flex;
  align-items: center;
  padding: 0 24px;
  box-sizing: border-box;
=======
@import url('https://fonts.googleapis.com/css2?family=Sorts+Mill+Goudy&display=swap');

/* NAVBAR */
.pup-navbar {
  position: fixed;
  top: 0;
  width: 100%;
  height: 80px;
  background-color: #800000;
  color: white;
  display: flex;
  align-items: center;
  padding: 0 24px;
  z-index: 1000;
>>>>>>> Stashed changes
}

.navbar-container {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

<<<<<<< Updated upstream
/* --- Left Section --- */
=======
/* BRANDING */
>>>>>>> Stashed changes
.navbar-branding {
  display: flex;
  align-items: center;
  gap: 15px;
}

<<<<<<< Updated upstream
/* START: Logo Image Styles (Replacing .logo-circle and .logo-text) */
.logo-image-container {
  width: 45px;
  height: 45px;
  background-color: #800000; /* Use PUP red/maroon as the background */
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  /* Retain the white border requested */
  border: 2px solid white; 
  /* Add padding to prevent the image from touching the border */
  padding: 4px; 
  box-sizing: border-box;
=======
.logo-image-container {
  width: 54px;
  height: 54px;
  border-radius: 50%;
  overflow: hidden;
>>>>>>> Stashed changes
}

.pup-logo-img {
  width: 100%;
  height: 100%;
<<<<<<< Updated upstream
  /* Ensures the image covers the container while preserving aspect ratio */
  object-fit: contain; 
}
/* END: Logo Image Styles */


.university-info {
  display: flex;
  flex-direction: column;
}

/* Updated Font Styles */
.uni-name {
  font-family: 'Sorts Mill Goudy', serif;
  font-size: 1.4rem;
  font-weight: normal;
  margin: 0;
  line-height: 1;
  color: #FFFFFF;
  letter-spacing: 0.5px;
}

.system-name {
  font-family: 'Sorts Mill Goudy', serif;
  font-size: 1rem;
  margin-top: 4px;
  font-weight: normal;
  color: #FFFFFF;
  letter-spacing: 1px;
}

/* --- Right Section --- */
=======
  object-fit: cover;
}

.uni-name {
  font-family: 'Sorts Mill Goudy', serif;
  font-size: 1.4rem;
  margin: 0;
  line-height: 1;
}

.system-name {
  font-size: 1rem;
}

/* LINKS */
>>>>>>> Stashed changes
.navbar-actions {
  display: flex;
  align-items: center;
  gap: 30px;
<<<<<<< Updated upstream
  margin-right: 10px;
=======
>>>>>>> Stashed changes
}

.top-links {
  display: flex;
<<<<<<< Updated upstream
  align-items: center;
=======
>>>>>>> Stashed changes
  gap: 15px;
}

.header-link {
<<<<<<< Updated upstream
  color: #ffc107;
  text-decoration: none;
  font-weight: 700;
  font-size: 1.05rem;
  text-transform: uppercase;
  transition: color 0.2s;
}

.header-link:hover {
  color: white;
  text-decoration: underline;
=======
  color: white;
  font-weight: bold;
  text-decoration: none;
>>>>>>> Stashed changes
}

.separator {
  color: white;
<<<<<<< Updated upstream
  font-weight: 300;
  font-size: 1.2rem;
  margin-top: -2px;
}

/* --- User Menu --- */
=======
}

/* USER MENU */
>>>>>>> Stashed changes
.user-menu {
  position: relative;
}

.user-button {
  display: flex;
  align-items: center;
<<<<<<< Updated upstream
  background: transparent;
  border: none;
  cursor: pointer;
  gap: 8px;
  color: white;
  padding: 5px 10px;
  border-radius: 4px;
}

.user-button:hover {
  background-color: rgba(255,255,255, 0.1);
}

.user-label-text {
  font-weight: bold;
  margin-right: 5px;
  color: white;
=======
  gap: 8px;
  background: transparent;
  border: none;
  color: white;
  cursor: pointer;
>>>>>>> Stashed changes
}

.user-avatar-container {
  width: 35px;
  height: 35px;
  border-radius: 50%;
<<<<<<< Updated upstream
  overflow: hidden;
  background-color: #eee;
  border: 2px solid white;
}

.user-avatar {
=======
  background: #ffc107;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
}

/* DROPDOWN */
.user-dropdown {
  position: absolute;
  top: 110%;
  right: 0;
  background: white;
  border-radius: 10px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.18);
  overflow: hidden;
}

/* HEADER */
.user-dropdown-header {
  padding: 18px 32px;
  border-bottom: 1px solid #e6e6e6;
}

.user-header-content {
  display: flex;
  align-items: center;
  gap: 12px;
}

.dropdown-user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  overflow: hidden;
  background-color: #ffc107;
  display: flex;
  align-items: center;
  justify-content: center;
}

.dropdown-user-avatar img {
>>>>>>> Stashed changes
  width: 100%;
  height: 100%;
  object-fit: cover;
}

<<<<<<< Updated upstream
.user-avatar-initials {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #ffc107;
  color: #800000;
  font-weight: bold;
}

/* --- Dropdown Styles --- */
.user-dropdown {
  position: absolute;
  top: 120%;
  right: 0;
  width: 220px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.15);
  border: 1px solid #e0e0e0;
  overflow: hidden;
  color: #333; 
}

.user-dropdown-header {
  padding: 16px;
  background-color: #f8f9fa;
  border-bottom: 1px solid #eee;
}

.user-name {
  font-weight: 600;
  font-size: 0.95rem;
}

.user-email {
  font-size: 0.8rem;
  color: #666;
}

.user-dropdown-menu {
  list-style: none;
  padding: 8px 0;
  margin: 0;
}

.user-dropdown-item {
  display: flex;
  align-items: center;
  padding: 10px 16px;
  color: #333;
  text-decoration: none;
  gap: 10px;
  width: 100%;
  background: none;
  border: none;
  cursor: pointer;
  text-align: left;
  font-size: 0.9rem;
}

.user-dropdown-item:hover {
  background-color: #f0f0f0;
  color: #800000;
}

.divider {
  height: 1px;
  background-color: #eee;
  margin: 8px 0;
}

.logout {
  color: #dc3545;
}

.logout:hover {
  background-color: #fff1f1;
  color: #dc3545;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
  .uni-name {
    font-size: 1rem; 
  }
  .top-links {
    display: none; 
  }
  .user-label-text {
    display: none;
  }
}
</style>
=======
.user-name {
  font-size: 18px;
  font-weight: 600;
  color: #000000
}

.user-email {
  font-size: 12px;
  color: #777;
}

/* MENU */
.user-dropdown-menu {
  list-style: none;
  margin: 0;
  padding: 0;
}

.dropdown-row {
  border-bottom: 1px solid #eee;
}

.dropdown-link {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 6px 40px;
  width: 100%;
  font-size: 15px;
  background: none;
  border: none;
  cursor: pointer;
  color: #000000;
  text-decoration: none;
  font-family: 'Sorts Mill Goudy', serif;
}

.dropdown-row.active .dropdown-link {
  background-color: #800000;
  color: white;
}

.dropdown-row:not(.active) .dropdown-link:hover {
  background-color: #800000;
  color: #ffffff;
}

.arrow {
  font-size: 1.5rem;
  color: #999;
}

.dropdown-row.active .arrow {
  color: white;
}

.dropdown-link:hover .arrow {
  color: #ffffff;
}
</style>
>>>>>>> Stashed changes
