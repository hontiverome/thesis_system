<!---
 * System Name: Theming and UI Framework
 * Module Name: User Dropdown Popover
 * Purpose Of this file: 
 * To display the contents of user dropdown popover
 * 
 * Author: Jerome Andrei O. Hontiveros
 * Copyright (C) 2025
 * by the Department of Science and Technology — Project LODI
 * All rights reserved.
 * 
 * Permission is hereby granted, free of charge, to any persons obtaining a copy
 * of this software and associated documentation files, to deal in the Software
 * without restriction, including the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, provided that the
 * above copyright notice(s) and this permission notice appears in all copies of
 * the Software and that both the above copyright notice(s) and this permission
 * notice appear in supporting documentation.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT OF THIRD PARTY RIGHTS.
 * IN NO EVENT SHALL THE COPYRIGHT HOLDER OR HOLDERS INCLUDED IN THIS NOTICE BE
 * LIABLE FOR ANY CLAIM, OR ANY SPECIAL INDIRECT OR CONSEQUENTIAL DAMAGES, OR ANY
 * DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
 * ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF OR IN
 * CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 * 
 * Except as contained in this notice, the name of a copyright holder shall not
 * be used in advertising or otherwise to promote the sale, use or other dealings
 * in this Software without prior written authorization of the copyright holder.
-->

<template>
  <transition
  name="dropdown"
  @before-enter="beforeEnter"
  @after-leave="afterLeave"
>
  <div 
    v-show="isOpen"
    ref="popoverRef"
    class="sidebar-dropdown-popover"
    v-click-outside="handleClickOutside"
    :style="popoverStyle"
  >
    <div class="sidebar-dropdown-header">
      <div class="sidebar-avatar-container">
        <template v-if="userStore.user?.avatar">
          <img :src="userStore.user.avatar" :alt="userStore.user.name || 'User'" class="sidebar-avatar" />
        </template>
        <div v-else class="sidebar-avatar-initials">
          {{ userStore.userInitials }}
        </div>
      </div>
      <div class="sidebar-user-info">
        <div class="sidebar-user-name">{{ userStore.user?.firstName }} {{ userStore.user?.lastName || '' }}</div>
        <div class="sidebar-user-email">{{ userStore.user?.email || '' }}</div>
        <div class="user-status">
          <span class="status-badge" :class="userStore.user?.status || 'active'">
                    {{ userStore.statusOptions.find(s => s.value === (userStore.user?.status || 'active'))?.label || 'Active' }}
          </span>        
        </div>
      </div>
    </div>
    <ul class="sidebar-dropdown-menu">
      <li>
        <router-link to="/profile" class="sidebar-dropdown-item" @click="close">
          <IconifyIcon icon="mdi:account" class="sidebar-menu-icon" />
          <span>My Profile</span>
        </router-link>
      </li>
      <li>
        <router-link to="/settings" class="sidebar-dropdown-item" @click="close">
          <IconifyIcon icon="mdi:cog" class="sidebar-menu-icon" />
          <span>Settings</span>
        </router-link>
      </li>
      <li class="sidebar-divider"></li>
      <li>
        <button class="sidebar-dropdown-item sidebar-logout" @click="handleLogout">
          <IconifyIcon icon="mdi:logout" class="sidebar-menu-icon" />
          <span>Sign out</span>
        </button>
      </li>
    </ul>
  </div>
</transition>
</template>

<script>
// Define the click-outside directive in a normal script block
export default {
  directives: {
    'click-outside': {
      beforeMount(el, binding) {
        el.__clickHandler__ = (event) => {
          // Check if the click is outside the element and its children
          if (!(el === event.target || el.contains(event.target))) {
            binding.value(event);
          }
        };
        // Use mousedown instead of click to handle cases where mouseup might happen outside
        document.addEventListener('mousedown', el.__clickHandler__);
      },
      unmounted(el) {
        document.removeEventListener('mousedown', el.__clickHandler__);
      }
    }
  }
};
</script>

<script setup>
import { useUserStore } from '@/stores/user';
import { Icon } from '@iconify/vue';
import { ref, onMounted, onUnmounted, watch, defineProps, defineEmits, nextTick } from 'vue';
import { Icon as IconifyIcon } from '@iconify/vue';

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  targetElement: {
    type: [HTMLElement, Object],
    default: null
  },
  isSidebarCollapsed: {
    type: Boolean,
    default: false
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
  if (!props.isOpen) return;
  
  const targetEl = props.targetElement?.$el || props.targetElement;
  if (!targetEl) return;
  
  const rect = targetEl.getBoundingClientRect();
  const viewportWidth = window.innerWidth;
  const popoverWidth = 280; // Width of the popover
  const offset = 32; // Space between button and popover
  
  let left, top;
  
  if (props.isSidebarCollapsed) {
    // When sidebar is collapsed, position the popover to the right of the collapsed sidebar
    left = rect.right + offset;
    top = rect.top + (rect.height / 2) - 240; // Center vertically with the button
  } else {
    // When sidebar is expanded, position to the right of the user button
    left = rect.right + offset;
    top = rect.top - 205; // Slight vertical adjustment
  }
  
  // If popover would go off-screen to the right, show it on the left side instead
  if (left + popoverWidth > viewportWidth - 20) {
    left = rect.left - popoverWidth - offset;
  }
  
  // Ensure popover stays within viewport bounds
  if (left < 10) left = 10;
  if (top < 10) top = 10;
  
  updatePopoverStyle(left, top, popoverWidth);
};

const updatePopoverStyle = (left, top, width = 280) => {
  popoverStyle.value = {
    position: 'fixed',
    top: `${top}px`,
    left: `${left}px`,
    width: `${width}px`,
    opacity: props.isOpen ? 1 : 0,
    visibility: props.isOpen ? 'visible' : 'hidden',
    transform: props.isOpen ? 'scale(1)' : 'scale(0.95)',
    'transform-origin': 'top left',
    'transition': 'all 0.15s ease-out',
    'z-index': 1000,
    'pointer-events': props.isOpen ? 'auto' : 'none'
  };
};

watch(() => props.isOpen, async (isOpen) => {
  console.log('isOpen changed to:', isOpen);
  
  if (isOpen) {
    // Wait for the next tick to ensure the DOM is updated
    await nextTick();
    updatePosition();
    window.addEventListener('resize', updatePosition);
    window.addEventListener('scroll', updatePosition, true);
  } else {
    window.removeEventListener('resize', updatePosition);
    window.removeEventListener('scroll', updatePosition, true);
  }
  
  // Update popover style with current position or default values
  const currentStyle = popoverStyle.value;
  updatePopoverStyle(
    currentStyle.left ? parseInt(currentStyle.left) : 0,
    currentStyle.top ? parseInt(currentStyle.top) : 0,
    currentStyle.width ? parseInt(currentStyle.width) : 280
  );
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

const handleClickOutside = (event) => {
  const targetEl = props.targetElement?.$el || props.targetElement;
  if (targetEl && (targetEl === event.target || targetEl.contains(event.target))) {
    return;
  }
  close();
};

onMounted(() => {
  if (props.isOpen) {
    updatePosition();
  }
});

watch(() => props.isOpen, async (isOpen) => {
  if (isOpen) {
    await nextTick();
    updatePosition();
    window.addEventListener('resize', updatePosition);
    window.addEventListener('scroll', updatePosition, true);
  } else {
    window.removeEventListener('resize', updatePosition);
    window.removeEventListener('scroll', updatePosition, true);
  }
  
  const currentStyle = popoverStyle.value;
  updatePopoverStyle(
    currentStyle.left ? parseInt(currentStyle.left) : 0,
    currentStyle.top ? parseInt(currentStyle.top) : 0,
    currentStyle.width ? parseInt(currentStyle.width) : 280
  );
}, { immediate: true });

const beforeEnter = (el) => {
  el.style.opacity = '0';
  el.style.transform = 'translateY(-10px) scale(0.95)';
};


const handleLogout = async () => {
  await userStore.logout();
  close();
};
</script>

