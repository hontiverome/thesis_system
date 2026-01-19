<template>
  <header class="pup-navbar">
    <div class="navbar-container">
      
      <div class="navbar-branding">
        <div class="logo-image-container">
          <router-link to="/home" class="logo-image-container">
          <img 
            :src="logoImage" 
            alt="Polytechnic University of the Philippines Logo" 
            class="pup-logo-img" 
          />
        </router-link>
        </div>
        <div class="university-info">
          <h1 class="uni-name">Polytechnic University of the Philippines</h1>
          <span class="system-name">T-SIS</span>
        </div>
      </div>

      <div class="navbar-actions">
        
        <nav class="top-links">
          <router-link to="/home" class="header-link">HOMEPAGE</router-link>
          <span class="separator">|</span>
          <router-link to="/courses" class="header-link">COURSE</router-link>
        </nav>

        <div class="user-menu" @click.stop="toggleUserMenu">
          <button class="user-button" aria-label="User menu" :aria-expanded="isUserMenuOpen">
            <span class="user-label-text">USER</span>
            
            <div class="user-avatar-container">
              <div class="user-avatar-initials">
                {{ userStore.user?.firstName ? userStore.user.firstName.charAt(0).toUpperCase() : 'U' }}
              </div>
            </div>
            <IconifyIcon class="icon-chevron" :class="{ 'rotate-180': isUserMenuOpen }" icon="mdi:chevron-down" />
          </button>
          
          <div v-if="isUserMenuOpen" class="user-dropdown active">
            
            <div class="user-dropdown-header">
              <div class="user-info">
                <div class="user-name">{{ userStore.user?.firstName }} {{ userStore.user?.lastName || '' }}</div>
                <div class="user-email">{{ userStore.user?.email || '' }}</div>
              </div>
            </div>

            <ul class="user-dropdown-menu">
              <li class="dropdown-row">
                <router-link to="/profile" class="dropdown-link" @click="closeUserMenu">
                  <div class="link-left">
                    <IconifyIcon icon="mdi:account" class="menu-icon" />
                    <span>Profile</span>
                  </div>
                  <span class="arrow">›</span>
                </router-link>
              </li>

              <li class="dropdown-row">
                <router-link to="/notif" class="dropdown-link" @click="closeUserMenu">
                  <div class="link-left">
                    <IconifyIcon icon="mdi:bell" class="menu-icon" />
                    <span>Notification</span>
                  </div>
                  <span class="arrow">›</span>
                </router-link>
              </li>

              <li class="dropdown-row">
                <router-link to="/help" class="dropdown-link" @click="closeUserMenu">
                  <div class="link-left">
                    <IconifyIcon icon="mdi:help-circle" class="menu-icon" />
                    <span>Help & Support</span>
                  </div>
                  <span class="arrow">›</span>
                </router-link>
              </li>

              <li class="dropdown-row">
                <router-link to="/settings" class="dropdown-link" @click="closeUserMenu">
                  <div class="link-left">
                    <IconifyIcon icon="mdi:cog" class="menu-icon" />
                    <span>Settings</span>
                  </div>
                  <span class="arrow">›</span>
                </router-link>
              </li>

              <li class="dropdown-row logout">
                <button class="dropdown-link logout-btn" @click="handleLogout">
                  <div class="link-left">
                    <IconifyIcon icon="mdi:logout" class="menu-icon" />
                    <span>Logout</span>
                  </div>
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
import { onMounted, ref, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { useLayoutStore } from '@/stores/layout.js';
import { useUserStore } from '@/stores/user.js';
import { loadIcons } from '@iconify/vue';
import { useAuth } from '@/composables/useAuth';

// Import Logo Image safely
import logoImage from '../../../assets/PUP_logo.png';

const router = useRouter();
const layoutStore = useLayoutStore();
const userStore = useUserStore();

// Destructure logout from useAuth
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
    await logout();
    closeUserMenu();
    router.push('/login/student');
  } catch (error) {
    console.error('Logout failed:', error);
    router.push('/login/student');
  }
};

const handleClickOutside = (event) => {
  const menu = document.querySelector('.user-menu')
  if (menu && !menu.contains(event.target)) {
    closeUserMenu()
  }
};

onMounted(() => {
  // Load all required icons
  loadIcons([
    'mdi:account', 
    'mdi:chevron-down', 
    'mdi:cog', 
    'mdi:logout',
    'mdi:bell',       // Added for Notification
    'mdi:help-circle' // Added for Help
  ]);
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
});
</script>

<style scoped>
/* Import the Sorts Mill Goudy font */
@import url('https://fonts.googleapis.com/css2?family=Sorts+Mill+Goudy&display=swap');

/* PUP NAVBAR CONTAINER */
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
}

.navbar-container {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

/* --- Left Section (Branding) --- */
.navbar-branding {
  display: flex;
  align-items: center;
  gap: 15px;
}

.logo-image-container {
  width: 60px;
  height: 60px;
  background-color: #800000;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-sizing: border-box;
  padding:0 0 5px 0;
  cursor: pointer;
}

.pup-logo-img {
  width: 100%;
  height: 100%;
  object-fit: contain; 
}

.university-info {
  display: flex;
  flex-direction: column;
}

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

/* --- Right Section (Actions) --- */
.navbar-actions {
  display: flex;
  align-items: center;
  gap: 30px;
  margin-right: 10px;
}

.top-links {
  display: flex;
  align-items: center;
  gap: 15px;
}

.header-link {
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
}

.separator {
  color: white;
  font-weight: 300;
  font-size: 1.2rem;
  margin-top: -2px;
}

/* --- User Menu Button --- */
.user-menu {
  position: relative;
}

.user-button {
  display: flex;
  align-items: center;
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
}

.user-avatar-container {
  width: 35px;
  height: 35px;
  border-radius: 50%;
  overflow: hidden;
  background-color: #ffc107;
  border: 2px solid white;
}

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
  width: 240px; /* Slightly wider to fit content */
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
  font-size: 1rem;
  color: #000;
}

.user-email {
  font-size: 0.8rem;
  color: #666;
  word-break: break-all; /* Ensures long emails don't break layout */
}

/* Dropdown List Items */
.user-dropdown-menu {
  list-style: none;
  padding: 0;
  margin: 0;
}

.dropdown-row {
  border-bottom: 1px solid #f0f0f0;
}

/* Link Container */
.dropdown-link {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 20px;
  width: 100%;
  color: #333;
  text-decoration: none;
  background: white;
  border: none;
  cursor: pointer;
  text-align: left;
  font-size: 0.95rem;
  font-family: inherit;
  transition: background 0.2s;
}

/* Group Icon and Text on the Left */
.link-left {
  display: flex;
  align-items: center;
  gap: 12px;
}

/* Icon Styling */
.menu-icon {
  font-size: 1.2rem;
  color: #666;
}

.dropdown-link:hover {
  background-color: #800000;
  color: white;
}

.dropdown-link:hover .menu-icon {
  color: white;
}

.arrow {
  font-size: 1.2rem;
  color: #999;
}

.dropdown-link:hover .arrow {
  color: white;
}

/* Logout Specifics */
.logout-btn {
  color: #dc3545;
}

.logout-btn .menu-icon {
  color: #dc3545;
}

.logout-btn:hover {
  background-color: #dc3545;
  color: white;
}

.logout-btn:hover .menu-icon {
  color: white;
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