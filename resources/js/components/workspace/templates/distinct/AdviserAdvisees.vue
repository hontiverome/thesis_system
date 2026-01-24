<template>
  <div class="adviser-workspace-container">
    <h1 class="page-title">LIST OF CLASS ENROLLEES</h1>
    <h2 class="section-subtitle">SECTION #</h2>

    <div class="workspace-wrapper">
      <div class="card-tab-container">
        <button 
          class="card-tab-btn" 
          :class="{ active: activeTab === 'groups' }" 
          @click="activeTab = 'groups'"
        >
          GROUPS
        </button>
        <button 
          class="card-tab-btn" 
          :class="{ active: activeTab === 'enrollees' }" 
          @click="activeTab = 'enrollees'"
        >
          ENROLLEES
        </button>
      </div>

      <div class="main-content-card">
        
        <div v-if="activeTab === 'groups'" class="view-container">
          <div class="sub-view-toggle">
            <button 
              :class="['toggle-pill', { active: groupSubView === 'list' }]" 
              @click="groupSubView = 'list'"
            >
              <Icon icon="lucide:user" v-if="groupSubView === 'list'" class="mr-1" /> Group List
            </button>
            <button 
              :class="['toggle-pill', { active: groupSubView === 'submission' }]" 
              @click="groupSubView = 'submission'"
            >
              View Group Submission
            </button>
          </div>

          <div v-if="groupSubView === 'list'">
            <label class="input-label">Create a Group</label>
            <div class="form-row-grid">
              <select v-model="form.code" class="styled-select">
                <option value="" disabled>Group Code</option>
                <option v-for="c in codes" :key="c">{{ c }}</option>
              </select>
              
              <select v-model="form.leader" class="styled-select">
                <option value="" disabled>Assign a leader</option>
                <option v-for="l in availableLeaders" :key="l">{{ l }}</option>
              </select>

              <select class="styled-select">
                <option value="" disabled>Choose a members</option>
              </select>
              
              <button class="btn-create-yellow" @click="handleAddGroup">Create</button>
            </div>

            <div class="stats-display-row">
              <div class="stat-box"><strong>CLASS SECTION:</strong> 3-3</div>
              <div class="stat-box"><strong>NO. OF GROUPS:</strong> {{ groups.length || 'N' }}</div>
            </div>

            <table class="pup-data-table">
              <thead>
                <tr>
                  <th>Group Code</th>
                  <th>Group Leader</th>
                  <th>Group Members</th>
                  <th class="search-cell">
                    <div class="table-search-box">
                      <input type="text" placeholder="Search by Group Code/Name" v-model="groupSearch" />
                      <Icon icon="lucide:search" />
                    </div>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(g, i) in groups" :key="i">
                  <td class="text-center">{{ g.code }}</td>
                  <td class="text-center">{{ g.leader }}</td>
                  <td class="text-center text-sm">{{ g.member.join(', ') }}</td>
                  <td class="text-center action-icons">✏️ 🗑️</td>
                </tr>
                <tr v-if="groups.length === 0">
                  <td colspan="4" class="empty-placeholder">
                    No Groups Yet, <span class="gold-link" @click="focusGroupInput">Create a Group.</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-else class="submissions-view">
             </div>
        </div>

        <div v-else class="view-container">
          <label class="input-label">Search Enrollees or Add Them</label>
          <div class="enroll-action-row">
            <div class="search-bar-pill" :class="{ 'focused': isSearchFocused }">
              <input 
                type="text" 
                v-model="enrolleeSearch"
                placeholder="Search by Student Number/Name" 
                @focus="isSearchFocused = true" 
                @blur="isSearchFocused = false" 
              />
              <div class="icon-divider"><Icon icon="lucide:search" /></div>
            </div>
            <button class="btn-enroll-yellow">ENROLL</button>
          </div>

          <div class="stats-display-row mt-4">
            <div class="stat-box"><strong>CLASS SECTION:</strong> 3-3</div>
            <div class="stat-box"><strong>NO. OF ENROLLEES:</strong> {{ enrollees.length || 'N' }}</div>
          </div>

          <div class="empty-state-large">
            No Enrollees Yet. Send Invitation or <span class="gold-link">Add Them Manually.</span>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Icon } from '@iconify/vue';

// Tab Logic
const activeTab = ref('groups');
const groupSubView = ref('list');
const isSearchFocused = ref(false);

// State
const groups = ref([]);
const enrollees = ref([]);
const groupSearch = ref('');
const enrolleeSearch = ref('');

// Form Logic
const form = ref({ code: '', leader: '', member: [] });
const codes = ['G-001', 'G-002', 'G-003'];
const availableLeaders = ['Juan Dela Cruz', 'Maria Clara', 'Andres Bonifacio'];

const handleAddGroup = () => {
  if (form.value.code && form.value.leader) {
    groups.value.push({ ...form.value });
    form.value = { code: '', leader: '', member: [] };
  }
};
</script>

<style scoped>
/* GENERAL STYLING (PUP Branding) */
.adviser-workspace-container { padding: 40px; font-family: 'Inter', sans-serif; }
.page-title { color: #800000; font-weight: 800; font-size: 26px; margin-bottom: 5px; }
.section-subtitle { color: #800000; font-weight: 700; font-size: 20px; margin-bottom: 30px; }

/* 🎨 CARD TAB BUTTONS (Matches Screenshot 040521) */
.card-tab-container { display: flex; gap: 4px; }
.card-tab-btn {
  padding: 14px 45px;
  background: #E5E7EB; /* Light grey for inactive */
  border: 1px solid #D1D5DB;
  border-bottom: none;
  font-weight: 800;
  font-size: 15px;
  color: #374151;
  cursor: pointer;
  border-radius: 12px 12px 0 0;
  transition: 0.2s;
}
.card-tab-btn.active {
  background: #FFFFFF;
  color: #111827;
  position: relative;
  z-index: 5;
  box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
}
.card-tab-btn.active::after {
  content: ''; position: absolute; bottom: -2px; left: 0; width: 100%; height: 5px; background: #fff;
}

/* MAIN CONTENT CARD */
.main-content-card { 
  background: #FFFFFF; border: 1px solid #D1D5DB; border-radius: 0 12px 12px 12px; 
  margin-top: -1px; min-height: 550px; padding: 40px; box-shadow: 0 4px 25px rgba(0,0,0,0.03);
}

/* SUB-TOGGLE PILLS (Matches image_fb6794.png) */
.sub-view-toggle { display: flex; justify-content: flex-end; gap: 8px; margin-bottom: 25px; }
.toggle-pill { 
  background: transparent; color: #9CA3AF; border: none; font-weight: 700; 
  padding: 10px 18px; cursor: pointer; display: flex; align-items: center; 
}
.toggle-pill.active { background: #EDC35E; color: white; border-radius: 8px; }

/* FORMS & INPUTS (Matches Screenshot 040521) */
.input-label { display: block; font-size: 14px; font-weight: 700; color: #4B5563; margin-bottom: 10px; }
.form-row-grid { display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 15px; margin-bottom: 35px; }
.styled-select { 
  border: 1px solid #D1D5DB; border-radius: 8px; padding: 12px; 
  background-color: #F9FAFB; font-size: 14px; color: #374151; 
}
.btn-create-yellow, .btn-enroll-yellow { 
  background: #EDC35E; color: #fff; font-weight: 800; border-radius: 8px; 
  padding: 0 35px; border: none; cursor: pointer; text-transform: uppercase;
}

/* ENROLL SEARCH BAR (Matches Screenshot 040536) */
.enroll-action-row { display: flex; gap: 15px; margin-bottom: 30px; }
.search-bar-pill { 
  display: flex; align-items: center; border: 1px solid #D1D5DB; 
  border-radius: 8px; width: 450px; background: #fff; transition: 0.2s;
}
.search-bar-pill.focused { border: 2px solid #EDC35E; box-shadow: 0 0 8px rgba(237, 195, 94, 0.2); }
.search-bar-pill input { border: none; flex: 1; padding: 12px 18px; outline: none; font-size: 14px; }
.icon-divider { padding: 0 15px; border-left: 1px solid #E5E7EB; color: #9CA3AF; }

/* DATA TABLES (Matches image_fb6794.png) */
.pup-data-table { width: 100%; border-collapse: collapse; }
.pup-data-table th { 
  padding: 18px 15px; color: #1F2937; border-bottom: 2px solid #E5E7EB; 
  text-align: center; font-weight: 800; font-size: 14px; 
}
.pup-data-table td { padding: 16px; border-bottom: 1px solid #F3F4F6; color: #374151; }
.table-search-box { 
  display: flex; align-items: center; border: 1px solid #D1D5DB; 
  border-radius: 20px; padding: 5px 12px; background: #fff;
}
.table-search-box input { border: none; font-size: 11px; width: 100%; outline: none; }

/* STATS BAR */
.stats-display-row { display: flex; gap: 60px; margin-bottom: 20px; font-size: 14px; color: #111827; }

/* UTILS */
.gold-link { color: #EDC35E; font-weight: 800; text-decoration: underline; cursor: pointer; }
.empty-placeholder, .empty-state-large { 
  text-align: center; padding: 100px 0; color: #6B7280; font-size: 18px; 
}
</style>