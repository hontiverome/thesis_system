<template>
  <div class="notification-container" ref="container">
    <div 
      class="bell-trigger" 
      @click="toggleDropdown" 
      role="button" 
      aria-label="Toggle Notifications"
    >
      <Icon icon="basil:notification-outline" class="bell-icon" />
      <span v-if="hasUnread" class="red-badge"></span>
    </div>

    <Teleport to="body">
      <Transition name="slide-fade">
        <div 
          v-if="isOpen" 
          class="notif-card"
          :style="dropdownStyles"
        >
          <div class="notif-header">
            <h3>{{ title }}</h3>
            <button @click="isOpen = false" class="close-btn">&times;</button>
          </div>
          
          <div v-if="notifications.length > 0" class="notif-scroll-area">
            <div 
              v-for="(notif, index) in notifications" 
              :key="notif.id || index"
              class="notif-item"
              :class="{ 'unread-bg': !notif.isRead }"
              @click="handleLinkClick(notif)"
            >
              <div class="notif-content">
                <p class="notif-body">
                  <strong>{{ notif.groupCode }}</strong> invited you to be their panel.
                </p>
                <span class="action-link">Click to see details</span>
              </div>
            </div>
          </div>
          
          <div v-else class="empty-state">
            <p>No new notifications</p>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Icon } from '@iconify/vue';

const props = defineProps({
  notifications: {
    type: Array,
    required: true,
    default: () => []
  },
  title: {
    type: String,
    default: 'Notifications'
  },
  // New Prop: Allows devs to align the popup to the left or right of the bell
  align: {
    type: String,
    default: 'right',
    validator: (value) => ['left', 'right'].includes(value)
  }
});

const emit = defineEmits(['view-invitations']);

const isOpen = ref(false);
const container = ref(null);
const dropdownStyles = ref({ top: '0px', left: '0px', position: 'absolute' });

const hasUnread = computed(() => {
  return props.notifications.some(n => !n.isRead);
});

// Dynamic positioning logic
const updatePosition = () => {
  if (container.value) {
    const rect = container.value.getBoundingClientRect();
    const isRightAligned = props.align === 'right';
    const cardWidth = 300; // Matches CSS width

    dropdownStyles.value = {
      top: `${rect.bottom + window.scrollY + 10}px`,
      // If right-aligned, match the right edge of the bell. 
      // If left-aligned, match the left edge of the bell.
      left: isRightAligned 
        ? `${rect.right + window.scrollX - cardWidth}px` 
        : `${rect.left + window.scrollX}px`,
      position: 'absolute'
    };
  }
};

const toggleDropdown = () => {
  if (!isOpen.value) updatePosition();
  isOpen.value = !isOpen.value;
};

const handleLinkClick = (notif) => {
  emit('view-invitations', notif);
  isOpen.value = false;
};

const handleClickOutside = (event) => {
  if (isOpen.value && container.value) {
    const isClickInsideBell = container.value.contains(event.target);
    const card = document.querySelector('.notif-card');
    const isClickInsideCard = card && card.contains(event.target);

    if (!isClickInsideBell && !isClickInsideCard) {
      isOpen.value = false;
    }
  }
};

onMounted(() => {
  window.addEventListener('click', handleClickOutside);
  window.addEventListener('scroll', updatePosition);
  window.addEventListener('resize', updatePosition);
});

onUnmounted(() => {
  window.removeEventListener('click', handleClickOutside);
  window.removeEventListener('scroll', updatePosition);
  window.removeEventListener('resize', updatePosition);
});
</script>

<style scoped>
.notification-container {
  display: inline-block;
  vertical-align: middle;
}

.bell-trigger {
  cursor: pointer;
  position: relative;
  padding: 4px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s;
}

.bell-trigger:hover {
  background: rgba(0, 0, 0, 0.05);
}

.bell-icon {
  font-size: 28px;
  color: #8b1a1a;
}

.red-badge {
  position: absolute;
  top: 4px;
  right: 4px;
  width: 10px;
  height: 10px;
  background-color: #d32f2f;
  border-radius: 50%;
  border: 2px solid white;
}

.notif-card {
  width: 300px;
  max-height: 450px;
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  z-index: 99999; /* Very high to stay on top */
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.notif-header {
  padding: 14px 16px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #fff;
  border-bottom: 1px solid #eee;
}

.notif-header h3 {
  margin: 0;
  font-size: 1rem;
  font-weight: 700;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.4rem;
  cursor: pointer;
  color: #bbb;
  line-height: 1;
}

.notif-scroll-area {
  overflow-y: auto;
  flex-grow: 1;
}

.notif-item {
  padding: 12px 16px;
  border-bottom: 1px solid #f5f5f5;
  cursor: pointer;
  transition: background 0.2s;
}

.notif-item:hover {
  background-color: #fafafa;
}

.unread-bg {
  background-color: #f0f7ff;
}

.notif-body {
  margin: 0;
  font-size: 0.88rem;
  color: #333;
  line-height: 1.4;
}

.action-link {
  font-size: 0.8rem;
  color: #8b1a1a;
  font-weight: 600;
  text-decoration: underline;
  margin-top: 4px;
  display: inline-block;
}

.empty-state {
  padding: 40px 20px;
  text-align: center;
  color: #999;
  font-size: 0.9rem;
}

/* Transitions */
.slide-fade-enter-active, .slide-fade-leave-active {
  transition: all 0.2s ease-out;
}
.slide-fade-enter-from, .slide-fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>