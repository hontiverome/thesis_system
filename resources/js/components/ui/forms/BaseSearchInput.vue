<template>
  <div class="base-search-wrapper relative w-full h-full" ref="searchContainer">
    <input
      :type="type"
      :value="modelValue"
      @input="handleInput"
      @focus="showDropdown = true"
      class="search-input-field block w-full text-sm transition-all duration-200 outline-none h-full"
      :class="[isPill ? 'rounded-full' : 'rounded-md']"
      :placeholder="placeholder || 'Search...'"
    />
    
    <div class="absolute inset-y-0 right-2 flex items-center pointer-events-none">
      <div class="h-2/3 border-l border-gray-300 mr-3"></div>
      <BaseIcon name="mdi:magnify" size="20px" color="#9CA3AF" />
    </div>

    <ul 
      v-if="showDropdown && filteredResults.length > 0" 
      class="search-dropdown shadow-lg border border-gray-200"
    >
      <li 
        v-for="(item, index) in filteredResults" 
        :key="index"
        @click="selectItem(item)"
        class="dropdown-item"
      >
        {{ item[searchKey] }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import BaseIcon from '../core/BaseIcon.vue';

const props = defineProps({
  modelValue: String,
  placeholder: String,
  type: { type: String, default: 'text' },
  isPill: { type: Boolean, default: false },
  
  // DATA PROPS FOR OTHER DEVS
  sourceData: { type: Array, default: () => [] }, // The list (Enrollees, Groups, etc.)
  searchKey: { type: String, default: 'name' },   // What property to filter by
  limit: { type: Number, default: 5 }             // Max suggestions to show
});

const emit = defineEmits(['update:modelValue', 'result']);

const showDropdown = ref(false);
const searchContainer = ref(null);

// Logic: Filter the sourceData based on the searchKey
const filteredResults = computed(() => {
  if (!props.modelValue || props.modelValue.length < 1) return [];
  
  const query = props.modelValue.toLowerCase();
  
  return props.sourceData
    .filter(item => {
      const valueToSearch = String(item[props.searchKey] || '').toLowerCase();
      return valueToSearch.includes(query);
    })
    .slice(0, props.limit); // Apply the limit
});

const handleInput = (e) => {
  emit('update:modelValue', e.target.value);
  showDropdown.value = true;
};

const selectItem = (item) => {
  // 1. Update the input text to match selection
  emit('update:modelValue', item[props.searchKey]);
  // 2. Send the whole object back to the parent dev
  emit('result', item);
  showDropdown.value = false;
};

// Close dropdown when clicking outside
const handleClickOutside = (event) => {
  if (searchContainer.value && !searchContainer.value.contains(event.target)) {
    showDropdown.value = false;
  }
};

onMounted(() => document.addEventListener('click', handleClickOutside));
onUnmounted(() => document.removeEventListener('click', handleClickOutside));
</script>

<style scoped>
.search-input-field {
  background-color: #F4F6F9;
  border: 1px solid #F4F6F9;
  padding: 10px 45px 10px 15px;
}

.search-input-field:focus {
  border-color: #000;
  background-color: #fff;
}

.search-dropdown {
  position: absolute;
  top: 105%;
  left: 0;
  right: 0;
  background: white;
  border-radius: 8px;
  z-index: 50;
  max-height: 200px;
  overflow-y: auto;
  padding: 5px 0;
}

.dropdown-item {
  padding: 10px 15px;
  cursor: pointer;
  font-size: 14px;
  transition: background 0.2s;
}

.dropdown-item:hover {
  background-color: #f3f4f6;
}
</style>