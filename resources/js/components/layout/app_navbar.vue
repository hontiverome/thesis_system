<template>
  <header class="pup-navbar">
    <div class="navbar-container">
      
      <div class="navbar-branding">
        <div class="logo-image-container">
          <img 
            src="path/to/your/logo.png" 
            alt="Polytechnic University of the Philippines Logo" 
            class="pup-logo-img" 
          />
        </div>
        <div class="university-info">
          <h1 class="uni-name">Polytechnic University of the Philippines</h1>
          <span class="system-name">T-SIS</span>
        </div>
      </div>

      <div class="navbar-actions">
        
        <nav class="top-links">
          <a href="#" class="header-link">HOMEPAGE</a>
          <span class="separator">|</span>
          <a href="#" class="header-link">COURSE</a>
        </nav>

        <div class="user-menu" @click.stop="toggleUserMenu">
          <button class="user-button" aria-label="User menu" :aria-expanded="isUserMenuOpen">
            <span class="user-label-text">USER</span>
            
            <div class="user-avatar-container">
              <template v-if="userStore.user?.avatar">
                <img :src="userStore.user.avatar" :alt="userStore.user.name || 'User'" class="user-avatar" />
              </template>
              <div v-else class="user-avatar-initials">
                {{ userStore.userInitials }}
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
              <li>
                <router-link to="/profile" class="user-dropdown-item" @click="closeUserMenu">
                  <IconifyIcon icon="mdi:account" class="menu-icon" />
                  <span>Your Profile</span>
                </router-link>
              </li>
              <li>
                <router-link to="/settings" class="user-dropdown-item" @click="closeUserMenu">
                  <IconifyIcon icon="mdi:cog" class="menu-icon" />
                  <span>Settings</span>
                </router-link>
              </li>
              <li class="divider"></li>
              <li>
                <button class="user-dropdown-item logout" @click="handleLogout">
                  <IconifyIcon icon="mdi:logout" class="menu-icon" />
                  <span>Sign out</span>
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

const handleClickOutside = (event) => {
  const userMenu = document.querySelector('.user-menu');
  if (userMenu && !userMenu.contains(event.target)) {
    closeUserMenu();
  }
};

const router = useRouter();
const layoutStore = useLayoutStore();
const userStore = useUserStore();
const auth = useAuth();

const isUserMenuOpen = ref(false);

const toggleUserMenu = () => {
  isUserMenuOpen.value = !isUserMenuOpen.value;
};

const closeUserMenu = () => {
  isUserMenuOpen.value = false;
};

const handleLogout = async () => {
  try {
    await auth.logout();
    userStore.clearUser();
    closeUserMenu();
    router.push({ name: 'login' });
  } catch (error) {
    console.error('Logout failed:', error);
  }
};

onMounted(() => {
  loadIcons([
    'mdi:account', 'mdi:chevron-down', 'mdi:cog', 'mdi:logout'
  ]);
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
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
}

.navbar-container {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

/* --- Left Section --- */
.navbar-branding {
  display: flex;
  align-items: center;
  gap: 15px;
}

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
}

.pup-logo-img {
  width: 100%;
  height: 100%;
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

/* --- User Menu --- */
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
  background-color: #eee;
  border: 2px solid white;
}

.user-avatar {
  width: 100%;
  height: 100%;
  object-fit: cover;
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