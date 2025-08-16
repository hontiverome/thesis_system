<template>
  <div 
  class="user-dropdown-popover" 
  :class="{ 'is-visible': isOpen }" 
  v-click-outside="close"
  :style="popoverStyle"
>
    <div class="user-dropdown-header">
      <div class="user-avatar-container">
        <template v-if="userStore.user?.avatar">
          <img :src="userStore.user.avatar" :alt="userStore.user.name || 'User'" class="user-avatar" />
        </template>
        <div v-else class="user-avatar-initials">
          {{ userStore.userInitials }}
        </div>
      </div>
      <div class="user-info">
        <div class="user-name">{{ userStore.user?.name || 'User' }}</div>
        <div class="user-email">{{ userStore.user?.email || '' }}</div>
        <div class="user-status">
          <span class="status-badge active">Active</span>
        </div>
      </div>
    </div>
    <ul class="user-dropdown-menu">
      <li>
        <router-link to="/profile" class="user-dropdown-item" @click="close">
          <IconifyIcon icon="mdi:account" class="menu-icon" />
          <span>My Profile</span>
        </router-link>
      </li>
      <li>
        <router-link to="/settings" class="user-dropdown-item" @click="close">
          <IconifyIcon icon="mdi:cog" class="menu-icon" />
          <span>Settings</span>
        </router-link>
      </li>
      <li class="divider"></li>
      <li>
        <button class="user-dropdown-item logout" @click="handleLogout">
          <IconifyIcon icon="mdi:logout" class="menu-icon" />
          <span>Logout</span>
        </button>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { useUserStore } from '@/stores/user';
import { Icon } from '@iconify/vue';
import { ref, onMounted, onUnmounted, watch, defineProps, defineEmits } from 'vue';
import { Icon as IconifyIcon } from '@iconify/vue';

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  targetElement: {
    type: HTMLElement,
    default: null
  },
  position: {
    type: Object,
    default: () => ({
      top: 0,
      left: 0
    })
  }
});

const emit = defineEmits(['close']);

defineOptions({
  components: {
    IconifyIcon
  }
});
const userStore = useUserStore();
const popoverRef = ref(null);
const popoverStyle = ref({
  position: 'fixed',
  top: '0',
  left: '0',
  opacity: 0,
  visibility: 'hidden',
  transform: 'translateX(-10px)'
});

const updatePosition = () => {
  if (!props.targetElement) return;
  
  const rect = props.targetElement.getBoundingClientRect();
  const viewportWidth = window.innerWidth;
  const popoverWidth = 280; // Width of the popover
  const offset = 30; // Space between button and popover
  
  // Position the popover to the right of the user button
  let left = rect.right + offset;
  // Position higher by subtracting from the top position
  let top = rect.top + window.scrollY - 200; // Increased from 0 to -40 to move it up more
  
  // If popover would go off-screen to the right, show it on the left side instead
  if (left + popoverWidth > viewportWidth - 10) {
    left = rect.left - popoverWidth - offset;
  }
  
  popoverStyle.value = {
    ...popoverStyle.value,
    position: 'fixed',
    top: `${top}px`,
    left: `${left}px`,
    width: `${popoverWidth}px`,
    opacity: props.isOpen ? 1 : 0,
    visibility: props.isOpen ? 'visible' : 'hidden',
    transform: props.isOpen ? 'translateX(0)' : 'translateX(-10px)',
    'transform-origin': 'top left',
    'transition': 'all 0.15s ease-out',
    'z-index': 1000,
    'pointer-events': props.isOpen ? 'auto' : 'none'
  };
};

watch(() => props.isOpen, (isOpen) => {
  if (isOpen) {
    updatePosition();
    window.addEventListener('resize', updatePosition);
    window.addEventListener('scroll', updatePosition, true);
  } else {
    window.removeEventListener('resize', updatePosition);
    window.removeEventListener('scroll', updatePosition, true);
  }
}, { immediate: true });

onMounted(() => {
  if (props.isOpen) {
    updatePosition();
  }
});

onUnmounted(() => {
  window.removeEventListener('resize', updatePosition);
  window.removeEventListener('scroll', updatePosition, true);
});

const close = () => {
  emit('close');
};

const handleLogout = async () => {
  await userStore.logout();
  close();
};
</script>

<style scoped>
.user-dropdown-popover {
  position: fixed;
  width: 280px;
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 0.5rem;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  transition: all 0.2s ease;
  z-index: 1000;
  pointer-events: none;
  transform-origin: top left;
}

.user-dropdown-popover.is-visible {
  pointer-events: auto;
}

.user-dropdown-header {
  padding: 1.25rem 1.5rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  border-bottom: 1px solid var(--border-color);
  background-color: var(--card-bg);
}

.user-avatar-container {
  width: 3rem;
  height: 3rem;
  border-radius: 50%;
  overflow: hidden;
  background-color: var(--primary-color);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 600;
  flex-shrink: 0;
}

.user-avatar {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.user-info {
  flex: 1;
  min-width: 0;
}

.user-name {
  font-weight: 600;
  color: var(--text-color);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.user-email {
  font-size: 0.875rem;
  color: var(--text-muted);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.user-dropdown-menu {
  list-style: none;
  padding: 0.5rem 0;
  margin: 0;
}

.user-dropdown-item {
  display: flex;
  align-items: center;
  padding: 0.75rem 1.5rem;
  color: var(--text-color);
  text-decoration: none;
  transition: background-color 0.2s ease;
  cursor: pointer;
  background: none;
  border: none;
  width: 100%;
  text-align: left;
  font-size: 0.9375rem;
}

.user-dropdown-item:hover {
  background-color: var(--hover-color);
}

.user-dropdown-item.logout {
  color: var(--danger);
}

.user-dropdown-item.logout:hover {
  background-color: var(--danger-bg);
  color: #fff;
}

.user-dropdown-item.logout:hover .menu-icon {
  color: #fff !important;
}

.menu-icon {
  margin-right: 0.75rem;
  width: 1.25rem;
  height: 1.25rem;
  flex-shrink: 0;
}

.divider {
  height: 1px;
  background-color: var(--border-color);
  margin: 0.5rem 0;
}

.status-badge {
  display: inline-block;
  font-size: 0.75rem;
  font-weight: 500;
  padding: 0.15rem 0.5rem;
  border-radius: 9999px;
  background-color: #d1fae5;
  color: #065f46;
  margin-top: 0.25rem;
}

.status-badge.active {
  background-color: #d1fae5;
  color: #065f46;
}
</style>
