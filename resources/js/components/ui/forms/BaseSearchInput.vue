<template>
  <div class="base-search-input-wrapper relative h-full">
    <input
      :type="type"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
     
      class="block w-full pl-10 pr-14 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring h-full rounded-md"
      :style="{
        backgroundColor: '#F4F6F9',
        border: '1px solid #F4F6F9',
        paddingLeft: '10px',
        paddingRight: '10px'
      }"
     
      :placeholder="placeholder || 'search...'"
     
      onfocus="this.style.border='1px solid black'"
      onblur="this.style.border='1px solid #F4F6F9'"
    />
   
    <div
      class="absolute inset-y-0 flex items-center"
      style="right: 8px;"
    >
     
      <div
        class="h-2/3 border-l border-gray-300"
        style="margin-right: 12px;"
      ></div>


      <BaseIcon name="mdi:magnify" size="20px" color="#9CA3AF" />
    </div>
  </div>
</template>


<script setup>
// ... (script remains unchanged)
import BaseIcon from '../core/BaseIcon.vue';


defineProps({
  modelValue: String,
  placeholder: String,
  type: {
    type: String,
    default: 'search'
  },
  isPill: {
    type: Boolean,
    default: false
  }
});


defineEmits(['update:modelValue']);
</script>
