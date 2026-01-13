<template>
  <div class="list-view">
    <h2 class="form-title">Create a Group</h2>
    <div class="create-grid custom-layout">
      <button @click="addGroup" class="btn-create-submit">Create</button>
    </div>

    <div class="table-container">
      <input v-model="searchQuery" placeholder="Search Groups..." class="search-bar" />
      <table>
        </table>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
const props = defineProps(['options']);

const groups = ref([]);
const searchQuery = ref('');
const form = ref({ code: '', leader: '', member: '' });

const addGroup = () => {
  if (form.value.code) {
    groups.value.push({ ...form.value });
    form.value = { code: '', leader: '', member: '' };
  }
};

const filteredGroups = computed(() => {
  return groups.value.filter(g => g.code.includes(searchQuery.value));
});
</script>