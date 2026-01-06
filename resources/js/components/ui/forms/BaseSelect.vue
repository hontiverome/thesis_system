<template>
  <div class="base-select-wrapper">
    <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700">
      {{ label }}
    </label>
    <select
      :id="id"
      :value="modelValue"
      @change="$emit('update:modelValue', $event.target.value)"
      class="mt-1 block w-full rounded-md border border-gray-300 bg-white p-2 appearance-none shadow-sm focus:border-blue-500 focus:ring-blue-500"
    >
      <option disabled value="">{{ placeholder || 'Select an option' }}</option>
      <option v-for="option in options" :key="option.value" :value="option.value">
        {{ option.text }}
      </option>
    </select>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  modelValue: [String, Number],
  label: String,
  placeholder: String,
  options: {
    type: Array, // Array of { value: string|number, text: string }
    required: true
  }
});

// Generate a unique ID for accessibility
const id = computed(() => props.label ? props.label.toLowerCase().replace(/\s/g, '-') : `select-${Math.random().toString(36).substring(2, 9)}`);

defineEmits(['update:modelValue']);
</script>