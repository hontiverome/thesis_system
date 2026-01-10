<template>
  <nav class="pagination-wrapper" aria-label="Pagination Navigation">
    <button 
      class="arrow" 
      @click="updatePage(modelValue - 1)" 
      :disabled="modelValue <= 1"
      aria-label="Previous page"
    >
      &lt;
    </button>

    <ul class="page-list">
      <li 
        v-for="page in totalPages" 
        :key="page"
        class="page-num"
        :class="{ active: modelValue === page }"
        @click="updatePage(page)"
        role="button"
        :aria-current="modelValue === page ? 'page' : undefined"
      >
        {{ page }}
      </li>
    </ul>

    <button 
      class="arrow" 
      @click="updatePage(modelValue + 1)" 
      :disabled="modelValue >= totalPages"
      aria-label="Next page"
    >
      &gt;
    </button>
  </nav>
</template>

<script setup>
/**
 * PROPS: Allows parent components to control this component
 */
const props = defineProps({
  modelValue: {
    type: Number,
    required: true
  },
  totalPages: {
    type: Number,
    required: true,
    default: 1
  }
});

/**
 * EMITS: Sends data back to the parent
 */
const emit = defineEmits(['update:modelValue', 'change']);

const updatePage = (newPage) => {
  // Boundary check to ensure page stays within valid range
  if (newPage >= 1 && newPage <= props.totalPages) {
    emit('update:modelValue', newPage);
    emit('change', newPage);
  }
};
</script>

<style scoped>
.pagination-wrapper {
  /* Scope variables here so they are always active */
  --primary-color: #800000;
  --text-white: #ffffff;
  --disabled-color: #ccc;
  --hover-bg: #f5f5f5;

  display: flex;
  align-items: center;
  justify-content: center;
  gap: 2rem;
  font-family: sans-serif;
  user-select: none;
  padding: 1rem;
}

.arrow {
  background: none;
  border: none;
  color: var(--primary-color);
  font-size: 2.5rem;
  font-weight: 300;
  cursor: pointer;
  transition: opacity 0.2s;
  line-height: 1;
}

.arrow:disabled {
  color: var(--disabled-color);
  cursor: not-allowed;
}

.page-list {
  display: flex;
  list-style: none;
  padding: 0;
  margin: 0;
  gap: 1.5rem;
}

.page-num {
  width: 60px;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.75rem;
  color: var(--primary-color);
  cursor: pointer;
  border-radius: 50%;
  transition: all 0.3s ease;
}

.page-num.active {
  background-color: var(--primary-color);
  color: var(--text-white);
}

.page-num:not(.active):hover {
  background-color: var(--hover-bg);
}
</style>