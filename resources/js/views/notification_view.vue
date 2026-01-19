<template>
  <div class="page">
    <header class="page-header">
      <div class="header-content">
        <div class="header-left">
          <span class="header-title">NOTIFICATIONS</span>
        </div>
        <button class="btn-header-action" @click="markAllRead">
          Mark all as read
        </button>
      </div>
    </header>

    <div class="content">
      <div class="main-block">
        
        <div v-if="notifications.length === 0" class="empty-state">
          <span class="empty-icon">🔕</span>
          <p>No new notifications</p>
        </div>

        <div v-else class="notification-list">
          <div 
            v-for="item in notifications" 
            :key="item.id" 
            class="notification-item"
            :class="{ 'unread': !item.read }"
          >
            <div class="notif-icon" :class="item.type">
              {{ getIcon(item.type) }}
            </div>
            <div class="notif-content">
              <h4 class="notif-title">{{ item.title }}</h4>
              <p class="notif-desc">{{ item.message }}</p>
              <span class="notif-time">{{ item.time }}</span>
            </div>
            <button class="delete-btn" @click="removeNotification(item.id)">×</button>
          </div>
        </div>

      </div>
    </div>

    <footer class="footer">
       <span>T-SIS SYSTEM</span>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const notifications = ref([
  { 
    id: 1, 
    type: 'info', 
    title: 'System Update', 
    message: 'The system has been updated to version 2.0.', 
    time: '2 hours ago', 
    read: false 
  },
  { 
    id: 2, 
    type: 'success', 
    title: 'Profile Verified', 
    message: 'Your student profile has been successfully verified by the admin.', 
    time: '1 day ago', 
    read: true 
  },
  { 
    id: 3, 
    type: 'warning', 
    title: 'Password Expiry', 
    message: 'Your password will expire in 3 days. Please update it soon.', 
    time: '2 days ago', 
    read: true 
  }
]);

const getIcon = (type) => {
  const icons = { info: 'ℹ️', success: '✅', warning: '⚠️', error: '❌' };
  return icons[type] || '📌';
};

const markAllRead = () => {
  notifications.value.forEach(n => n.read = true);
};

const removeNotification = (id) => {
  notifications.value = notifications.value.filter(n => n.id !== id);
};
</script>

<style scoped>
/* BASE LAYOUT (Inherited from Profile) */
* { box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }

.page {
  background: #f4f4f4;
  min-height: 100vh;
  width: 100%;
  display: flex;
  flex-direction: column;
}

/* HEADER */
.page-header {
  background: linear-gradient(to right, #e8891c, #f6d2a3);
  border-bottom: 1px solid #e0e0e0;
  padding: 20px 0;
  width: 100%;
  flex-shrink: 0;
}

.header-content {
  padding: 0 50px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header-title {
  font-size: 30px;
  letter-spacing: 6px;
  font-weight: 900;
  color: #ffffff;
  display: block;
}

.header-subtitle {
  margin: 5px 0 0 0;
  color: rgba(255, 255, 255, 0.9);
  font-size: 13px;
  font-weight: 600;
  letter-spacing: 1px;
}

.btn-header-action {
  font-size: 13px;
  background: rgba(255,255,255,0.2);
  border: 1px solid #fff;
  color: #fff;
  padding: 8px 20px;
  cursor: pointer;
  border-radius: 4px;
  font-weight: 700;
  text-transform: uppercase;
  transition: all 0.2s;
}
.btn-header-action:hover { background: #fff; color: #e8891c; }

/* CONTENT */
.content {
  display: flex;
  justify-content: center;
  padding: 40px;
  width: 100%;
  flex-grow: 1;
}

.main-block {
  width: 100%;
  max-width: 800px;
  background: white;
  padding: 0; /* Padding handled by items */
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  border: 1px solid #e0e0e0;
  height: fit-content;
}

/* NOTIFICATION LIST */
.notification-item {
  display: flex;
  gap: 20px;
  padding: 25px;
  border-bottom: 1px solid #eee;
  position: relative;
  transition: background 0.2s;
}

.notification-item:last-child { border-bottom: none; }
.notification-item:hover { background: #fafafa; }
.notification-item.unread { background: #fff8f0; border-left: 4px solid #e8891c; }

.notif-icon {
  font-size: 24px;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f4f4f4;
  border-radius: 50%;
  flex-shrink: 0;
}
.notif-icon.info { color: #2196F3; background: #e3f2fd; }
.notif-icon.success { color: #4CAF50; background: #e8f5e9; }
.notif-icon.warning { color: #FF9800; background: #fff3e0; }

.notif-content { flex: 1; }

.notif-title {
  margin: 0 0 5px 0;
  font-size: 16px;
  font-weight: 800;
  color: #333;
}

.notif-desc {
  margin: 0 0 10px 0;
  font-size: 14px;
  color: #666;
  line-height: 1.5;
}

.notif-time {
  font-size: 12px;
  font-weight: 600;
  color: #999;
  text-transform: uppercase;
}

.delete-btn {
  background: none;
  border: none;
  font-size: 20px;
  color: #ccc;
  cursor: pointer;
  padding: 0 10px;
  height: fit-content;
}
.delete-btn:hover { color: #cc0000; }

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

/* RESPONSIVE */
@media (max-width: 768px) {
  .content { padding: 20px; }
  .header-content { padding: 0 20px; }
  .header-title { font-size: 24px; }
  .btn-header-action { display: none; } /* Hide button on very small screens if needed */
}
</style>