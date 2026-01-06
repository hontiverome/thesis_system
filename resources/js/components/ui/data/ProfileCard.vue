<template>
  <BaseCard class="text-center p-4">
    <BaseAvatar :src="faculty.avatarUrl" :initials="faculty.initials" size="80px" class="mx-auto mb-3"/>
    
    <h4 class="text-lg font-bold text-gray-900 mb-1">{{ faculty.name }}</h4>
    
    <p v-if="faculty.quote" class="text-sm text-gray-500 italic mb-3">
      {{ faculty.quote }}
    </p>

    <BaseButton 
      v-if="showAddButton" 
      variant="green" 
      size="small" 
      @click="$emit('add-faculty', faculty.id)"
      class="mt-2"
    >
      +
    </BaseButton>
  </BaseCard>
</template>

<script setup>
import BaseCard from '../core/BaseCard.vue';
import BaseAvatar from '../core/BaseAvatar.vue';
import BaseButton from '../core/BaseButton.vue';

defineProps({
  faculty: {
    type: Object, // Expects { id, name, initials, avatarUrl, quote } [cite: 326]
    required: true
  },
  showAddButton: {
    type: Boolean,
    default: false // The + button on Page 15 [cite: 322]
  }
});

defineEmits(['add-faculty']);
</script>