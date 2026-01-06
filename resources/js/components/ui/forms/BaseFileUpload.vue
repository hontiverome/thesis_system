<template>
  <div 
    class="base-file-upload border-2 border-dashed rounded-lg p-6 text-center cursor-pointer transition"
    :class="[isDragging ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-blue-400']"
    @click="triggerFileSelect"
    @dragover.prevent="isDragging = true"
    @dragleave.prevent="isDragging = false"
    @drop.prevent="handleDrop"
  >
    <input 
      type="file" 
      ref="fileInput" 
      :accept="accept" 
      hidden 
      @change="handleFileChange" 
    />
    
    <BaseIcon name="upload-cloud" size="32px" class="mx-auto text-gray-400" />

    <p class="mt-2 text-sm text-gray-600 font-medium">
      {{ selectedFileName || 'Choose a file or drag & drop' }}
    </p>
    <p v-if="selectedFileName" class="text-xs text-green-600 mt-1">
      File ready for upload.
    </p>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import BaseIcon from '../core/BaseIcon.vue';

const props = defineProps({
  accept: {
    type: String,
    default: '.pdf,.doc,.docx' // Typical research document types [cite: 26]
  }
});

const emit = defineEmits(['file-selected']);
const fileInput = ref(null);
const isDragging = ref(false);
const selectedFileName = ref('');

const triggerFileSelect = () => {
  fileInput.value.click();
};

const handleFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    selectedFileName.value = file.name;
    emit('file-selected', file);
  }
};

const handleDrop = (event) => {
  isDragging.value = false;
  const file = event.dataTransfer.files[0];
  if (file) {
    selectedFileName.value = file.name;
    emit('file-selected', file);
    // Optionally set the file to the input element if needed for form submission
    fileInput.value.files = event.dataTransfer.files;
  }
};
</script>