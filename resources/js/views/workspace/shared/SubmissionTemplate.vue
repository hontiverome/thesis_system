// SubmissionTemplate is a shared template used for displaying submission-related information 
// and actions in various views for faculty, admin, advisers, and students.
<template>
  <div class="submission-container">
    <BaseCard :title="displayTitle">
      <div class="workspace-header">
        <div class="meta-info">
          <h3>GROUP #</h3>
          <h3>SECTION</h3>
        </div>
        <div class="adviser-info">
          <h3>ASSIGNED ADVISER</h3>
        </div>
      </div>

      <div class="content-area">
        <div v-if="hasUploadedFile" class="viewer-state">
          <div class="status-bar">
            <span>STATUS: {{ submissionStatus }}</span>
          </div>
          <iframe :src="fileUrl" class="pdf-viewer"></iframe>
        </div>

        <div v-else class="upload-state">
          <div class="upload-box">
            <p>Upload Your {{ displayTitle }}</p>
            <div class="drop-zone" @click="triggerFileInput">
               <p>Choose a file or drag & drop it here</p>
               <button class="browse-btn">Browse File</button>
            </div>
          </div>
        </div>
      </div>
    </BaseCard>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import BaseCard from '@/components/ui/core/BaseCard.vue';

const props = defineProps(['tab', 'role']);

// Logic to check if the student has already submitted something
const hasUploadedFile = ref(false); 
const fileUrl = ref('');
const submissionStatus = ref('PENDING');

const displayTitle = computed(() => props.tab.replace(/-/g, ' ').toUpperCase());
</script>

<style scoped>
.pdf-viewer { width: 100%; height: 600px; border: none; }
.drop-zone { border: 2px dashed #800000; padding: 40px; text-align: center; cursor: pointer; }
</style>