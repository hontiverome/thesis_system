<template>
  <div>
    <add-button @click="addNewTitle" />

    <add-button 
      label="Add New Chapter" 
      @click="addNewChapter" 
    />
  </div>
</template>

<script>
// Import the component
import AddButton from '@/components/core/AddButton.vue'; // Adjust path as needed

export default {
  components: {
    AddButton
  },
  methods: {
    addNewTitle() {
      console.log("Add Title clicked!");
      // Your logic to add a title goes here
    },
    addNewChapter() {
      console.log("Add Chapter clicked!");
      // Your logic to add a chapter goes here
    }
  }
}
</script>