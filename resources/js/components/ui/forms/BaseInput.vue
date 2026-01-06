<template>
  <div class="base-input-wrapper relative h-full">
    <input
      :type="type"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      
      class="block w-full pl-4 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring h-full rounded-md"
      :style="{
        backgroundColor: '#F4F6F9',
        border: '1px solid #F4F6F9', // Add camouflaged border for consistent behavior
        // 💡 MODIFIED: Adjusted padding to remove space reserved for the icon/divider
        paddingLeft: '10px',
        paddingRight: '10px' 
      }"
      
      :placeholder="placeholder || 'code...'"
      
      onfocus="this.style.border='1px solid black'"
      onblur="this.style.border='1px solid #F4F6F9'"
    />
    
    </div>
</template>


<script setup>

defineProps({
  modelValue: String,
  placeholder: String,
  type: {
    type: String,
    default: 'text' 
  },
  isPill: {
    type: Boolean,
    default: false
  }
});


defineEmits(['update:modelValue']);
</script>