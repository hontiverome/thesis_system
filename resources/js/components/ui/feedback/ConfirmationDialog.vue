<template>
  <BaseModal :isOpen="isOpen" @update:isOpen="val => $emit('update:isOpen', val)" title="Confirm Action">
    <p class="mb-4 text-gray-700">{{ message }}</p>

    <div class="flex justify-end space-x-3 mt-4">
      <BaseButton variant="grey" @click="$emit('cancel')">
        {{ cancelText }}
      </BaseButton>
      <BaseButton :variant="confirmVariant" @click="$emit('confirm')">
        {{ confirmText }}
      </BaseButton>
    </div>
  </BaseModal>
</template>

<script setup>
import BaseModal from './BaseModal.vue';
import BaseButton from '../core/BaseButton.vue';

defineProps({
  isOpen: Boolean,
  message: {
    type: String,
    default: 'Are you sure you want to proceed with this action?'
  },
  confirmText: {
    type: String,
    default: 'Confirm'
  },
  cancelText: {
    type: String,
    default: 'Cancel'
  },
  // Use 'dark-red' for delete/reject, 'green' for accept
  confirmVariant: {
    type: String,
    default: 'green',
    validator: (val) => ['dark-red', 'green'].includes(val)
  }
});

defineEmits(['update:isOpen', 'confirm', 'cancel']);
</script>