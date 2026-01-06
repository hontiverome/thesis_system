<template>
  <div class="page-container">
    
    <uploaded-file-card 
      file-name="Chapter1_Draft.pdf"
      @delete-file="showModal = true"
    />

    <delete-confirmation-modal 
      v-if="showModal"
      @cancel="showModal = false"
      @confirm="handleConfirm"
    />

  </div>
</template>

<script>
// Import your components
import UploadedFileCard from './UploadedFileCard.vue';
import DeleteConfirmationModal from './DeleteConfirmationModal.vue';

export default {
  name: 'SubmissionPage',
  components: {
    UploadedFileCard,
    DeleteConfirmationModal
  },
  data() {
    return {
      // THE TRAFFIC LIGHT
      // false = Hidden
      // true = Visible
      showModal: false
    }
  },
  methods: {
    handleConfirm() {
      // 1. Close the modal
      this.showModal = false;
      
      // 2. (Optional) Log it so you know it worked
      console.log("File has been deleted!");
      
      // Note for Dev 4 (Logic Dev):
      // "Hey, put the database delete code inside this handleConfirm function."
    }
  }
}
</script>

<style scoped>
.page-container {
  padding: 50px;
  background-color: #f4f4f4; /* Just for preview visibility */
  min-height: 100vh;
}
</style>