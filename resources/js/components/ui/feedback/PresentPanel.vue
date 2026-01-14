<template>
  <div v-if="isVisible" class="modal-overlay">
    <div class="modal-content">
      <button class="close-btn" @click="$emit('close')">×</button>

      <h2 class="modal-title">Select 3 Panelists Present on the Defense</h2>

      <div class="table-header">
        <span>PROFESSOR NAME</span>
        <span>ACTION</span>
      </div>

      <div class="professor-list">
        <div 
          v-for="(prof, index) in selectedPanelists" 
          :key="prof.id" 
          class="professor-row"
          :class="{ 'bg-gray': index % 2 === 0 }"
        >
          <div class="prof-info">
            <span class="star-icon selected-star">★</span>
            <span class="prof-name">{{ prof.name }}</span>
          </div>
          <button @click="removePanelist(prof)" class="action-btn btn-remove">Remove</button>
        </div>
        
        <div v-if="selectedPanelists.length === 0" class="empty-placeholder">
          No panelists added yet.
        </div>
      </div>

      <div class="search-section">
        <p class="search-label">Faculty not listed? Search and add:</p>
        <div class="search-wrapper">
          <div class="search-container">
            <input 
              v-model="searchQuery"
              type="text" 
              placeholder="Search by Faculty Name..." 
              class="search-input"
              @input="onSearchInput"
            />
            <button 
              class="add-to-list-btn" 
              :disabled="selectedCount >= 3 || !selectedFromSearch"
              @click="confirmAdd"
            >
              ADD TO LIST
            </button>
          </div>

          <ul v-if="showDropdown && filteredList.length" class="search-dropdown">
            <li 
              v-for="faculty in filteredList" 
              :key="faculty.id"
              @click="selectFromDropdown(faculty)"
            >
              {{ faculty.name }}
            </li>
          </ul>
        </div>
      </div>

      <div class="selection-footer">
        Selected: {{ selectedCount }} / 3
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

// 1. Define Props (Data coming from other devs)
const props = defineProps({
  isVisible: { type: Boolean, default: true },
  facultyList: { type: Array, default: () => [] } // The "Database" is now passed here
});

// 2. Define Emits (Sending data back to other devs)
const emit = defineEmits(['update:selected', 'close']);

const searchQuery = ref('');
const showDropdown = ref(false);
const selectedFromSearch = ref(null);
const selectedPanelists = ref([]);

const selectedCount = computed(() => selectedPanelists.value.length);

// 3. Logic: Filter based on the Prop instead of hardcoded data
const filteredList = computed(() => {
  const query = searchQuery.value.toLowerCase().trim();
  if (query.length < 1) return [];
  
  return props.facultyList.filter(f => 
    f.name.toLowerCase().includes(query) && 
    !selectedPanelists.value.some(p => p.id === f.id)
  );
});

const onSearchInput = () => {
  showDropdown.value = true;
  selectedFromSearch.value = null;
};

const selectFromDropdown = (faculty) => {
  searchQuery.value = faculty.name;
  selectedFromSearch.value = faculty;
  showDropdown.value = false;
};

const confirmAdd = () => {
  if (selectedFromSearch.value && selectedCount.value < 3) {
    selectedPanelists.value.push({ ...selectedFromSearch.value });
    
    // Notify parent of the change
    emit('update:selected', selectedPanelists.value);
    
    searchQuery.value = '';
    selectedFromSearch.value = null;
  }
};

const removePanelist = (prof) => {
  selectedPanelists.value = selectedPanelists.value.filter(p => p.id !== prof.id);
  // Notify parent of the change
  emit('update:selected', selectedPanelists.value);
};
</script>

<style scoped>
/* Keeping your exact original styles */
.modal-overlay {
  position: fixed; inset: 0; background: rgba(0, 0, 0, 0.4);
  display: flex; justify-content: center; align-items: center; z-index: 1000;
  font-family: sans-serif;
}
.modal-content {
  background: white; padding: 40px; border-radius: 20px; width: 480px; position: relative;
}
.close-btn {
  position: absolute; top: 15px; right: 15px; background: #ffebeb; color: #ff5c5c;
  border: none; border-radius: 50%; width: 28px; height: 28px; cursor: pointer;
}
.modal-title { text-align: center; font-weight: 550; margin-bottom: 30px; font-size: 22px;}
.table-header {
  display: flex; justify-content: space-between; padding: 0 10px 10px;
  font-size: 13px; font-weight: 800; border-bottom: 1px solid #eee;
}
.professor-list { min-height: 150px; margin-bottom: 20px; }
.professor-row {
  display: flex; justify-content: space-between; align-items: center; padding: 12px 10px;
}
.bg-gray { background-color: #f9f9f9; }
.prof-info { display: flex; align-items: center; gap: 10px; }
.star-icon { font-size: 18px; color: #ccc; }
.selected-star { color: #28a745; }
.prof-name { font-size: 14px; font-weight: 500; }
.action-btn { background: none; border: none; font-weight: bold; cursor: pointer; text-decoration: underline; }
.btn-remove { color: #dc3545; }
.search-section { position: relative; margin-top: 20px; }
.search-label { font-style: italic; font-size: 13px; color: #888; margin-bottom: 8px; }
.search-container {
  display: flex; gap: 10px; background: #f2f2f2; padding: 10px; border-radius: 12px;
}
.search-input {
  flex: 1; padding: 8px 15px; border-radius: 20px; border: 1px solid #ddd; outline: none;
}
.add-to-list-btn {
  background: #800000; color: white; border: none; padding: 0 15px;
  border-radius: 20px; font-size: 11px; font-weight: bold; cursor: pointer;
}
.add-to-list-btn:disabled { background: #ccc; cursor: not-allowed; }
.search-dropdown {
  position: absolute; top: 100%; left: 0; right: 0; background: white;
  border: 1px solid #ddd; border-radius: 8px; z-index: 10; margin: 5px 0;
  list-style: none; padding: 0; max-height: 120px; overflow-y: auto;
}
.search-dropdown li { padding: 10px 15px; cursor: pointer; font-size: 14px; }
.search-dropdown li:hover { background: #f0f0f0; }
.selection-footer {
  margin-top: 30px; text-align: center; font-weight: 800; font-size: 20px; color: #800000;
}
.empty-placeholder { text-align: center; color: #bbb; padding-top: 40px; font-style: italic; }
</style>