<template>
  <div class="page">
    <header class="profile-header">
      <div class="header-content">
        <span class="header-title">PROFILE</span>
        <button class="edit-btn-header" @click="showEditModal = true">
          Edit Profile
        </button>
      </div>
    </header>

    <div class="content">
      <aside class="card">
        <h2 class="user-name">{{ fullName }}</h2>
        <p class="user-id">@{{ userStore.user?.id || '33550336-MN-0' }}</p>

        <div class="avatar-wrapper">
          <img :src="avatar" class="avatar" />
        </div>

        <button class="btn orange">Upload New Photo</button>

        <div class="meta">
          <div class="meta-row">
            <strong>SYC:</strong> <span>3-3</span>
          </div>
          <div class="meta-row">
            <span class="group-label">GROUP #</span>
          </div>
        </div>

        <button class="btn dark" @click="handleLogout">Sign out</button>
      </aside>

      <section class="form">
        <div class="field">
          <label>About</label>
          <div class="box readonly-box">
             {{ userStore.user?.about || '33,550,336...' }}
          </div>
        </div>

        <div class="grid">
          <div class="field">
            <label>First Name</label>
            <div class="box readonly-box">
              {{ userStore.user?.firstName || 'Phainon' }}
              <button class="icon" @click="showEditModal = true">✎</button>
            </div>
          </div>
          <div class="field">
            <label>Last Name</label>
            <div class="box readonly-box">
              {{ userStore.user?.lastName || 'Khaslana' }}
              <button class="icon" @click="showEditModal = true">✎</button>
            </div>
          </div>
        </div>

        <div class="field">
          <label>Email</label>
          <div class="box readonly-box">
            {{ userStore.user?.email || 'email@address.com' }}
            <button class="icon" @click="showEditModal = true">✎</button>
          </div>
        </div>

        <div class="field">
          <label>Password</label>
          <div class="box readonly-box">
            <span>****************</span>
            <button class="icon" title="Change Password">✎</button>
          </div>
        </div>

        <div class="field">
          <label>Birthdate</label>
          <div class="box readonly-box">
             {{ userStore.user?.birthdate || 'Not set' }}
          </div>
        </div>
      </section>
    </div>

    <footer class="footer">
       <span>T-SIS SYSTEM</span>
    </footer>

    <EditProfileModal 
      v-if="showEditModal"
      :user="userStore.user"
      @close="showEditModal = false"
      @saved="handleProfileSaved"
    />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useUserStore } from '@/stores/user'
import { useRouter } from 'vue-router'
import EditProfileModal from '@/components/profile/edit_profile_modal.vue'

const userStore = useUserStore()
const router = useRouter()
const showEditModal = ref(false)
const avatar = ref('/avatar.png')

const fullName = computed(() => 
  `${userStore.user?.firstName || 'User'} ${userStore.user?.lastName || ''}`
)

const handleLogout = async () => {
    await userStore.logout();
    router.push('/login');
}

const handleProfileSaved = () => {
  // Refresh logic here if needed
}
</script>

<style scoped>
/* RESET & BASE */
* { box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }

.page {
  background: #f4f4f4;
  min-height: 100vh;
  width: 100%;
  display: flex;
  flex-direction: column;
}

/* TOP HEADER (Replaces Profile Strip) */
.profile-header {
  background: linear-gradient(to right, #e8891c, #f6d2a3);
  border-bottom: 1px solid #e0e0e0; /* Subtle separator */
  padding: 0;
  width: 100%;
  flex-shrink: 0;
}

.header-content {
  max-width: 100%; /* Or set a max-width if you want it contained */
  padding: 20px 50px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header-title {
  font-size: 30px;
  letter-spacing: 6px;
  font-weight: 900; /* Extra Bold */
  color: #ffffff;   /* Pure Black */
}

/* BUTTONS */
.edit-btn-header {
  font-size: 14px;
  background: white;
  border: 1px solid #000;
  color: #000;
  padding: 8px 20px;
  cursor: pointer;
  border-radius: 4px;
  font-weight: 600;
  transition: all 0.2s ease;
}
.edit-btn-header:hover { 
    background: #000; 
    color: white; 
}

/* CONTENT CONTAINER */
.content {
  display: flex;
  gap: 40px;
  padding: 40px;
  background: transparent;
  width: 100%;
  flex-grow: 1;
}

/* LEFT CARD */
.card {
  width: 320px;
  background: #fff;
  padding: 40px 30px;
  text-align: center;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  height: fit-content;
  flex-shrink: 0;
  border: 1px solid #e0e0e0;
}

.user-name {
  margin: 0;
  font-size: 26px;
  font-weight: 800;
  color: #000000;
}

.user-id {
  color: #cc0000;
  margin-bottom: 25px;
  margin-top: 5px;
  font-weight: 600;
  font-size: 14px;
}

.avatar-wrapper {
  margin: 0 auto 20px auto;
  width: 160px;
  height: 160px;
  border-radius: 50%;
  padding: 4px;
  border: 2px solid #e8891c;
}

.avatar {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  object-fit: cover;
  background: #ddd;
}

/* Side Buttons */
.btn {
  width: 100%;
  padding: 12px;
  margin-top: 14px;
  border: none;
  cursor: pointer;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-size: 13px;
}
.orange { background: #e8891c; color: white; }
.orange:hover { background: #d67a10; }

.dark { background: #000; color: white; }
.dark:hover { background: #333; }

/* Meta Info */
.meta {
  margin: 25px 0;
  font-size: 14px;
  color: #000;
  border-top: 1px solid #eee;
  border-bottom: 1px solid #eee;
  padding: 15px 0;
}
.meta-row {
  margin-bottom: 5px;
  display: flex;
  justify-content: center;
  gap: 5px;
}
.group-label {
  color: #cc0000; 
  font-weight: bold;
}

/* FORM STYLING */
.form { 
    flex: 1; 
    background: white;
    padding: 40px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid #e0e0e0;
}

.grid { 
  display: grid; 
  grid-template-columns: 1fr 1fr; 
  gap: 20px; 
}

.field { 
  margin-bottom: 24px; 
}

label { 
  font-weight: 800;
  margin-bottom: 8px; 
  display: block; 
  color: #000000; 
  font-size: 13px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.box { 
  position: relative; 
}

.readonly-box {
  width: 100%;
  padding: 12px 15px;
  border: 1px solid #e8891c;
  background: #fff;
  min-height: 48px;
  color: #000000;
  font-weight: 600;
  font-size: 16px;
  display: flex;
  align-items: center;
}

.icon {
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  border: none;
  background: none;
  cursor: pointer;
  font-size: 1.2rem;
  color: #000; 
  opacity: 0.3;
  transition: opacity 0.2s;
}
.icon:hover { 
  opacity: 1;
  color: #e8891c; 
}

/* FOOTER */
.footer {
  background: linear-gradient(to right, #e8891c, #f6d2a3);
  padding: 15px 40px;
  text-align: right;
  color: white;
  font-weight: 700;
  letter-spacing: 1px;
  margin-top: auto;
  flex-shrink: 0;
}

/* Responsive */
@media (max-width: 900px) {
    .content { flex-direction: column; padding: 20px; }
    .card { width: 100%; }
    .grid { grid-template-columns: 1fr; }
    .header-content { padding: 0 20px; }
}
</style>