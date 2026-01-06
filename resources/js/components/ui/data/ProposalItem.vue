<template>
  <div class="proposal-item bg-gray-100 rounded-md p-3 mb-2 flex justify-between items-center">
    <p class="text-sm text-gray-800 flex-grow mr-4">
      {{ title }} [cite: 119]
    </p>

    <div class="flex items-center space-x-2 flex-shrink-0">
      <template v-if="mode === 'adviser'">
        <BaseButton variant="green" size="small" @click="$emit('accept')">
          <BaseIcon name="check" size="14px" /> Accept
        </BaseButton>
        <BaseButton variant="dark-red" size="small" @click="$emit('reject')">
          <BaseIcon name="close" size="14px" /> Reject
        </BaseButton>
      </template>
      <template v-else-if="mode === 'student'">
        <BaseButton variant="grey" size="small" @click="$emit('upload')">
          Upload &gt;
        </BaseButton>
      </template>
      <template v-else-if="mode === 'status'">
        <StatusBadge :status="status" />
      </template>
    </div>
  </div>
</template>

<script setup>
import BaseButton from '../core/BaseButton.vue';
import BaseIcon from '../core/BaseIcon.vue';
import StatusBadge from './StatusBadge.vue';

defineProps({
  title: {
    type: String,
    required: true // e.g., "lorem ipsum dolor sit amet, consectetur..." [cite: 119]
  },
  // 'adviser' (shows accept/reject [cite: 121, 122]), 'student' (shows upload [cite: 35]), 'status' (shows badge)
  mode: {
    type: String,
    default: 'adviser',
    validator: (val) => ['adviser', 'student', 'status'].includes(val)
  },
  status: { // Only needed for 'status' mode
    type: String,
    default: 'pending'
  }
});

defineEmits(['accept', 'reject', 'upload']);
</script>