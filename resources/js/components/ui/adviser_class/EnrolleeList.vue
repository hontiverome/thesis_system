<template>
  <div class="enrollee-list-wrapper">
    <div class="top-bar">
      <div class="search-section">
        <label class="search-label">Search Enrollees or Add Them</label>
        <div 
          class="search-box" 
          :class="{ 'highlight-yellow': isSearchHighlighted }"
        >
          <input 
            ref="searchInput"
            type="text" 
            placeholder="Search by Student Number/Name" 
            v-model="searchQuery" 
            @focus="isSearchHighlighted = true"
            @blur="isSearchHighlighted = false"
          />
          <div class="search-icon-wrapper">
            <Icon icon="lucide:search" class="search-icon" />
          </div>
        </div>
      </div>
      <button class="btn-enroll" @click="handleEnrollClick">ENROLL</button>
    </div>

    <div class="stats-row">
      <div class="stat-item">
        <span class="stat-label">CLASS SECTION:</span>
        <span class="stat-value">{{ classSection }}</span>
      </div>
      <div class="stat-item">
        <span class="stat-label">NO. OF ENROLLEES:</span>
        <span class="stat-value">{{ enrollees.length }}</span>
      </div>
    </div>

    <div class="table-scroll-area">
      <table class="enrollee-table">
        <thead>
          <tr>
            <th class="col-no">No.</th>
            <th class="col-student-num">Student Number</th>
            <th class="col-name">Name</th>
            <th class="col-email">Email</th>
          </tr>
        </thead>
        <tbody>
          <template v-if="enrollees.length > 0">
            <tr v-for="(student, index) in enrollees" :key="student.studentNumber">
              <td class="col-no">{{ String(index + 1).padStart(2, '0') }}</td>
              <td class="col-student-num">{{ student.studentNumber }}</td>
              <td class="col-name">{{ student.name }}</td>
              <td class="col-email">{{ student.email || 'N/A' }}</td>
            </tr>
          </template>

          <tr v-else>
            <td colspan="4" class="empty-state-cell">
              <div class="empty-state-content">
                No Enrollees Yet. Send Invitation or 
                <a href="#" class="add-manually" @click.prevent="focusAndHighlightSearch">
                  Add Them Manually.
                </a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Icon } from '@iconify/vue';

const props = defineProps({
  classSection: { type: String, default: 'N/A' },
  enrollees: { type: Array, default: () => [] }
});

const emit = defineEmits(['enroll-student']);

const searchQuery = ref('');
const searchInput = ref(null);
const isSearchHighlighted = ref(false);

const focusAndHighlightSearch = () => {
  if (searchInput.value) {
    searchInput.value.focus();
  }
};

const handleEnrollClick = () => {
  if (!searchQuery.value.trim()) {
    focusAndHighlightSearch();
    return;
  }
  emit('enroll-student', searchQuery.value);
  searchQuery.value = '';
};
</script>

<style scoped>
.enrollee-list-wrapper { height: 100%; display: flex; flex-direction: column; }
.top-bar { display: flex; align-items: flex-end; gap: 20px; margin-bottom: 25px; flex-shrink: 0; }
.search-section { display: flex; flex-direction: column; gap: 8px; }
.search-label { font-size: 14px; color: #888; }

.search-box { 
  display: flex; 
  align-items: center; 
  border: 1px solid #ccc; 
  border-radius: 8px; 
  background: white; 
  width: 350px; 
  height: 42px;
  transition: all 0.2s ease;
}

/* Thicker Yellow Highlight */
.highlight-yellow {
  border: 2px solid #f1c40f !important;
  box-shadow: 0 0 5px rgba(241, 196, 15, 0.3);
}

.search-box input { border: none; padding: 0 15px; flex: 1; outline: none; background: transparent; font-size: 14px; }
.search-icon-wrapper { padding: 0 12px; border-left: 1px solid #eee; color: #666; display: flex; align-items: center; height: 100%; }
.btn-enroll { background-color: #f1c40f; color: white; border: none; padding: 0 30px; border-radius: 8px; font-weight: 700; height: 42px; cursor: pointer; }

.stats-row { display: flex; gap: 50px; margin-bottom: 20px; flex-shrink: 0; }
.stat-label { font-weight: 800; margin-right: 8px; font-size: 14px; color: #333; }
.stat-value { color: #000000; font-size: 14px; }

.table-scroll-area { flex-grow: 1; width: 100%; overflow-y: auto; overflow-x: hidden; background: white; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px; }
.enrollee-table { width: 100%; border-collapse: collapse; table-layout: fixed; }

th { position: sticky; top: 0; background: white; z-index: 10; padding: 18px 25px; text-align: center; font-weight: 700; font-size: 15px; color: #1e293b; border-bottom: 2px solid #f1f5f9; }
td { padding: 16px 25px; border-bottom: 1px solid #f1f5f9; color: #000000; font-size: 14px; }

.col-no { width: 10%; border-right: 1px solid #f1f5f9; text-align: center; }
.col-student-num { width: 25%; text-align: center; }
.col-name { width: 30%; text-align: left; padding-left: 20px !important; }
.col-email { width: 35%; text-align: left; padding-left: 20px !important; }

.empty-state-cell { height: 250px; text-align: center; vertical-align: middle; border: none; }
.empty-state-content { font-size: 16px; color: #333; }
.add-manually { color: #f1c40f; text-decoration: none; font-weight: 700; margin-left: 4px; cursor: pointer; }
.add-manually:hover { filter: brightness(0.9); }

.table-scroll-area::-webkit-scrollbar { width: 8px; }
.table-scroll-area::-webkit-scrollbar-track { background: transparent; }
.table-scroll-area::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>