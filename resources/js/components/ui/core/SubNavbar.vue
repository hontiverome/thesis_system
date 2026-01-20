<template>
  <div class="sub-navbar">
    <div class="tabs-container">
      <button
        v-for="tab in tabs"
        :key="tab"
        :class="['tab-btn', { active: modelValue === tab }]"
        @click="$emit('update:modelValue', tab)"
      >
        {{ formatTabName(tab) }}
      </button>
    </div>
  </div>
</template>

<script setup>
defineProps({
  tabs: {
    type: Array,
    required: true,
    default: () => []
  },
  modelValue: {
    type: String,
    required: true
  }
});

defineEmits(['update:modelValue']);

const formatTabName = (tab) => {
  return tab
    .split('-')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ');
};
</script>

<style scoped>
.sub-navbar {
  background-color: white;
  border-bottom: 2px solid #FFA500;
  margin-bottom: 30px;
  border-radius: 4px 4px 0 0;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.tabs-container {
  display: flex;
  gap: 0;
}

.tab-btn {
  padding: 16px 24px;
  background-color: transparent;
  border: none;
  cursor: pointer;
  font-size: 1rem;
  font-weight: 600;
  color: #666;
  transition: all 0.3s;
  border-bottom: 3px solid transparent;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.tab-btn:hover {
  background-color: #f5f5f5;
  color: #FFA500;
}

.tab-btn.active {
  color: #FFA500;
  border-bottom-color: #FFA500;
  background-color: #fffaf0;
}

@media (max-width: 768px) {
  .tab-btn {
    padding: 12px 16px;
    font-size: 0.9rem;
  }

  .tabs-container {
    overflow-x: auto;
  }
}
</style>
