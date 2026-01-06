<template>
  <transition name="modal">
    <div v-if="isOpen" class="modal-backdrop" @click.self="closeModal">
      <BaseCard class="modal-content" :style="{ width: maxWidth }" @click.stop>
        <div class="flex justify-between items-start mb-4">
          <h3 class="text-xl font-bold text-gray-900">{{ title }}</h3>
          <BaseButton variant="grey" size="small" @click="closeModal">
            <BaseIcon name="close" size="20px" />
          </BaseButton>
        </div>
        <slot></slot>
      </BaseCard>
    </div>
  </transition>
</template>

<script setup>
import BaseCard from '../core/BaseCard.vue';
import BaseIcon from '../core/BaseIcon.vue';
import BaseButton from '../core/BaseButton.vue';
import { onMounted, onUnmounted } from 'vue';

const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true
  },
  title: String,
  maxWidth: {
    type: String,
    default: '500px'
  }
});

const emit = defineEmits(['update:isOpen']);

const closeModal = () => {
  emit('update:isOpen', false);
};

// Handle escape key to close modal
const handleKeydown = (event) => {
  if (props.isOpen && event.key === 'Escape') {
    closeModal();
  }
};

onMounted(() => {
  document.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown);
});
</script>

<style scoped>
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5); /* Dark transparent overlay [cite: 208] */
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  max-height: 90vh;
  overflow-y: auto;
}

/* Vue Transition styles for smooth animation */
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
</style>