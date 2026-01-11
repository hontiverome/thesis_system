<template>
  <nav class="sub-nav">
    <ul class="nav-list">
      <li 
        v-for="item in items" 
        :key="item"
        class="nav-item"
        :class="{ active: modelValue === item }"
        @click="$emit('update:modelValue', item)"
      >
        {{ item }}
      </li>
    </ul>
  </nav>
</template>

<script setup>
/**
 * NO HARDCODED STRINGS HERE
 * items: The array of names (e.g. ['Submission', 'Feedback'])
 * modelValue: The specific string that should be Red (#720000)
 */
defineProps({
  items: {
    type: Array,
    required: true
  },
  modelValue: {
    type: String,
    required: true
  }
});

defineEmits(['update:modelValue']);
</script>

<style scoped>
.sub-nav {
  background-color: #E48319; 
  width: 100%; 
  padding: 12px 40px; 
  box-sizing: border-box;
}

.nav-list {
  display: flex;
  gap: 80px; 
  list-style: none;
  margin: 0;
  padding: 0;
}

.nav-item {
  color: #ffffff; /* White by default */
  font-family: 'Source Sans Pro', Arial, sans-serif;
  font-weight: 750; 
  cursor: pointer;
  transition: color 0.2s ease;
}

/* Logic: Turn Red only if it matches the modelValue */
.nav-item.active {
  color: #720000; 
}

.nav-item:hover {
  color: #720000;
}
</style>