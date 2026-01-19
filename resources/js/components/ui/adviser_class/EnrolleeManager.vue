<template>
  <div class="enrollee-manager-root">
    <EnrolleeList 
      :class-section="classSection" 
      :enrollees="currentEnrollees"
      @enroll-student="enrollNewStudent"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import EnrolleeList from './EnrolleeList.vue';

// Accept everything as props instead of hardcoding inside
const props = defineProps({
  classSection: { type: String, default: 'N/A' },
  // This is the initial list of students already in the class
  initialEnrollees: { type: Array, default: () => [] },
  // This is the "database" of all students on the site
  globalRegistry: { type: Array, default: () => [] }
});

// Create a local reactive copy of the enrollees so we can add to it
const currentEnrollees = ref([...props.initialEnrollees]);

const enrollNewStudent = (searchTerm) => {
  const query = searchTerm.toLowerCase();

  // 1. Search the "database" passed via props
  const studentFound = props.globalRegistry.find(s => 
    s.name.toLowerCase().includes(query) || 
    s.studentNumber.toLowerCase().includes(query)
  );

  if (studentFound) {
    // 2. Check for duplicates in the current list
    const isAlreadyEnrolled = currentEnrollees.value.some(
      s => s.studentNumber === studentFound.studentNumber
    );
    
    if (!isAlreadyEnrolled) {
      currentEnrollees.value.push(studentFound);
    } else {
      alert("Student is already in this class!");
    }
  } else {
    alert("Student not found in registered accounts.");
  }
};
</script>

<style scoped>
.enrollee-manager-root {
  width: 90%;
  height: 60vh;
  margin: 0 auto;
  padding: 10px 0;
  overflow: hidden;
  box-sizing: border-box;
}
</style>