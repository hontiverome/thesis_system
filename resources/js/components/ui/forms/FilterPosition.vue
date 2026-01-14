<template>
  <div class="filter-wrapper">
    <select 
      class="filter-select" 
      :value="modelValue" 
      @change="$emit('update:modelValue', $event.target.value)"
    >
      <option value="">Filter by Position</option>
      <option value="Full-Time">Full-Time Professor</option>
      <option value="Part-Time">Part-Time Lecturer</option>
      <option value="Department Head">Department Head</option>
      <option value="Committee">Committee Chair</option>
    </select>
    <Icon icon="lucide:chevron-down" class="dropdown-icon" />
  </div>
</template>

<script setup>
import { Icon } from '@iconify/vue';

defineProps({
  modelValue: String
});

defineEmits(['update:modelValue']);
</script>

<style scoped>
.filter-wrapper {
  position: relative;
  width: 100%;
  max-width: 500px;
}

.filter-select {
  width: 100%;
  padding: 12px 20px;
  font-size: 1rem;
  color: #9ca3af;
  background-color: white;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  appearance: none; /* Hides default arrow */
  cursor: pointer;
  transition: all 0.2s ease;
}

.filter-select:focus {
  outline: none;
  border-color: #a33131;
  box-shadow: 0 0 0 3px rgba(163, 49, 49, 0.1);
}

.dropdown-icon {
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
  color: #374151;
}
</style>