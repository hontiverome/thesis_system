<template>
  <div class="dashboard-container">
    
    <div class="dashboard-header">
      <div class="tabs-row">
        <div 
          v-for="tab in ['groups', 'enrollees']" 
          :key="tab"
          :class="['tab-item', { active: activeTab === tab }]" 
          @click="activeTab = tab"
        >
          {{ tab.toUpperCase() }}
        </div>
      </div>
    </div>

    <div class="main-card">
      
      <div v-if="activeTab === 'groups'" class="group-manager-section">
        
        <div class="sub-nav-actions">
          <button 
            :class="['btn-toggle', { active: groupView === 'list' }]" 
            @click="groupView = 'list'"
          >
            Group List
          </button>
          <button 
            :class="['btn-toggle', { active: groupView === 'submission' }]" 
            @click="groupView = 'submission'"
          >
            View Group Submission
          </button>
        </div>

        <div v-if="groupView === 'list'" class="group-list-view">
          <h2 class="section-title">Create a Group</h2>
          
          <div class="create-form-grid">
            <div class="input-wrap">
              <input v-model="groupForm.code" class="std-input" placeholder="Group Code (e.g. G-001)" />
            </div>

            <div class="input-wrap">
              <select v-model="groupForm.leader" class="std-input">
                <option value="" disabled>Assign a leader</option>
                <option v-for="student in availableStudents" :key="student" :value="student">{{ student }}</option>
              </select>
            </div>

            <div class="input-wrap">
              <select v-model="selectedMember" class="std-input" @change="addMemberToForm">
                <option value="" disabled>Add members</option>
                <option v-for="student in availableStudents" :key="student" :value="student">{{ student }}</option>
              </select>
              <div class="tags-row">
                <span v-for="m in groupForm.members" :key="m" class="member-tag">
                  {{ m }} <span class="remove-x" @click="removeMemberFromForm(m)">×</span>
                </span>
              </div>
            </div>

            <button class="btn-primary" @click="createGroup">CREATE</button>
          </div>

          <div class="table-wrapper">
            <table class="std-table">
              <thead>
                <tr>
                  <th>Group Code</th>
                  <th>Leader</th>
                  <th>Members</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="group in groups" :key="group.code">
                  <td class="text-center bold">{{ group.code }}</td>
                  <td class="text-center">{{ group.leader }}</td>
                  <td class="text-center">
                    <div class="stacked-text">
                      <span v-for="m in group.members" :key="m">{{ m }}</span>
                    </div>
                  </td>
                  <td class="text-center">
                    <div class="actions-cell">
                      <button class="btn-icon">✏️</button>
                      <button class="btn-icon">🗑️</button>
                    </div>
                  </td>
                </tr>
                <tr v-if="groups.length === 0">
                  <td colspan="4" class="empty-msg">No groups created yet.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div v-else class="group-submission-view">
          <div class="info-banner" v-if="classSection">
            <span class="label">CLASS SECTION:</span> 
            <span class="value">{{ classSection }}</span>
          </div>

          <div class="table-wrapper">
            <table class="std-table">
              <thead>
                <tr>
                  <th class="w-15 text-center">GROUP CODE</th>
                  <th class="w-40 text-center">RESEARCH TITLES</th>
                  <th class="w-15 text-center">STATUS</th>
                  <th class="w-30">
                    <select class="filter-select">
                      <option>Show All</option>
                      <option>Pending</option>
                      <option>Accepted</option>
                    </select>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="group in groups" :key="group.code">
                  <td class="text-center bold align-top pt-20">{{ group.code }}</td>
                  <td colspan="3" class="p-0">
                    <div 
                      v-for="(item, idx) in group.researchItems" 
                      :key="idx" 
                      class="research-row"
                    >
                      <div class="res-title">{{ item.title }}</div>
                      <div class="res-status">
                        <span :class="['badge', item.status.toLowerCase()]">{{ item.status }}</span>
                      </div>
                      <div class="res-actions">
                        <button 
                          class="btn-accept" 
                          :disabled="item.status !== 'Pending'"
                          @click="openDecisionModal('accept', item)"
                        >Accept</button>
                        <button 
                          class="btn-reject" 
                          :disabled="item.status !== 'Pending'"
                          @click="openDecisionModal('reject', item)"
                        >Reject</button>
                      </div>
                    </div>
                    <div v-if="!group.researchItems || group.researchItems.length === 0" class="empty-inner">
                      No submissions yet.
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <div v-else class="enrollees-section">
        
        <div class="top-bar-enrollee">
          <div class="search-group">
            <label>Search Enrollees or Add Them</label>
            <div class="search-field">
              <input 
                v-model="enrolleeSearch" 
                type="text" 
                placeholder="Search by Student Number/Name" 
              />
              <span class="icon">🔍</span>
            </div>
          </div>
          <button class="btn-enroll" @click="enrollStudent">ENROLL</button>
        </div>

        <div class="stats-panel">
          <div class="stat">
            <span class="lbl">CLASS SECTION:</span>
            <span class="val">{{ classSection }}</span>
          </div>
          <div class="stat">
            <span class="lbl">NO. OF ENROLLEES:</span>
            <span class="val">{{ enrollees.length }}</span>
          </div>
        </div>

        <div class="table-wrapper">
          <table class="std-table">
            <thead>
              <tr>
                <th class="w-10 text-center">No.</th>
                <th class="w-30 text-center">Student Number</th>
                <th class="w-30 text-left">Name</th>
                <th class="w-30 text-left">Email</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(student, i) in enrollees" :key="student.studentNumber">
                <td class="text-center">{{ i + 1 }}</td>
                <td class="text-center">{{ student.studentNumber }}</td>
                <td>{{ student.name }}</td>
                <td>{{ student.email }}</td>
              </tr>
              <tr v-if="enrollees.length === 0">
                <td colspan="4" class="empty-msg">No students enrolled.</td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>

    </div>

    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal-card">
        <h3 :class="modalAction === 'accept' ? 'text-green' : 'text-red'">
          Confirm {{ modalAction === 'accept' ? 'Acceptance' : 'Rejection' }}
        </h3>
        <p class="subtitle">Research: {{ selectedItem?.title }}</p>
        
        <div class="modal-input-group">
          <label>Reason / Comment:</label>
          <textarea v-model="commentText" placeholder="Provide feedback..."></textarea>
        </div>

        <div class="modal-footer">
          <button class="btn-cancel" @click="closeModal">Cancel</button>
          <button 
            :class="modalAction === 'accept' ? 'btn-confirm-accept' : 'btn-confirm-reject'"
            @click="confirmDecision"
          >
            Confirm Decision
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  classSection: { type: String, default: 'BSIT 4-1' }
});

// --- STATE: TABS & VIEWS ---
const activeTab = ref('groups'); // 'groups' | 'enrollees'
const groupView = ref('list');   // 'list' | 'submission'

// --- STATE: GROUPS ---
const groupForm = ref({ code: '', leader: '', members: [] });
const selectedMember = ref('');

// Mock Data: Existing Groups with their Submissions mixed in
const groups = ref([
  { 
    code: 'G-001', 
    leader: 'Dela Cruz, Juan', 
    members: ['Rizal, Jose', 'Bonifacio, Andres'],
    researchItems: [
      { id: 1, title: 'Automated Waste Management', status: 'Pending' },
      { id: 2, title: 'Library Kiosk System', status: 'Rejected' }
    ]
  }
]);

// Mock Data: Students available to be assigned
const availableStudents = ref([
  'Garcia, Maria', 'Santos, Ana', 'Luna, Antonio', 'Mabini, Apolinario'
]);

// --- STATE: ENROLLEES ---
const enrolleeSearch = ref('');
const enrollees = ref([
  { studentNumber: '2020-0001-TG-0', name: 'Rizal, Jose P.', email: 'jose@pup.edu.ph' }
]);
const studentRegistry = [
  { studentNumber: '2020-0002-TG-0', name: 'Bonifacio, Andres', email: 'andres@pup.edu.ph' },
  { studentNumber: '2020-0003-TG-0', name: 'Garcia, Maria', email: 'maria@pup.edu.ph' }
];

// --- STATE: MODAL ---
const showModal = ref(false);
const modalAction = ref(''); // 'accept' | 'reject'
const selectedItem = ref(null);
const commentText = ref('');

// ==========================
// METHODS: GROUPS
// ==========================
const addMemberToForm = () => {
  if (selectedMember.value && !groupForm.value.members.includes(selectedMember.value)) {
    groupForm.value.members.push(selectedMember.value);
    selectedMember.value = '';
  }
};

const removeMemberFromForm = (name) => {
  groupForm.value.members = groupForm.value.members.filter(m => m !== name);
};

const createGroup = () => {
  if (groupForm.value.code && groupForm.value.leader) {
    groups.value.push({
      code: groupForm.value.code,
      leader: groupForm.value.leader,
      members: [...groupForm.value.members],
      researchItems: []
    });
    // Reset Form
    groupForm.value = { code: '', leader: '', members: [] };
  } else {
    alert("Please enter a group code and select a leader.");
  }
};

// ==========================
// METHODS: SUBMISSIONS & MODAL
// ==========================
const openDecisionModal = (action, item) => {
  modalAction.value = action;
  selectedItem.value = item;
  commentText.value = '';
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  selectedItem.value = null;
};

const confirmDecision = () => {
  if (selectedItem.value) {
    selectedItem.value.status = modalAction.value === 'accept' ? 'Accepted' : 'Rejected';
    // Ideally, save commentText.value to backend here
    console.log(`Decision: ${modalAction.value}, Reason: ${commentText.value}`);
  }
  closeModal();
};

// ==========================
// METHODS: ENROLLEES
// ==========================
const enrollStudent = () => {
  if (!enrolleeSearch.value) return;
  const query = enrolleeSearch.value.toLowerCase();
  
  // Mock Search
  const found = studentRegistry.find(s => 
    s.name.toLowerCase().includes(query) || 
    s.studentNumber.toLowerCase().includes(query)
  );

  if (found) {
    const exists = enrollees.value.some(s => s.studentNumber === found.studentNumber);
    if (!exists) {
      enrollees.value.push(found);
      enrolleeSearch.value = '';
    } else {
      alert("Student already enrolled.");
    }
  } else {
    alert("Student not found in registry.");
  }
};
</script>

<style scoped>
/* GENERAL LAYOUT */
.dashboard-container {
  font-family: 'Inter', sans-serif;
  background-color: #fff;
  min-height: 100vh;
  padding: 40px;
}

/* HEADER TABS */
.dashboard-header {
  margin-bottom: 25px;
  border-bottom: 2px solid #f1f5f9;
}
.tabs-row {
  display: flex;
  gap: 10px;
}
.tab-item {
  padding: 12px 30px;
  cursor: pointer;
  font-weight: 700;
  color: #94a3b8;
  border-bottom: 3px solid transparent;
  margin-bottom: -2px;
  transition: all 0.2s;
  letter-spacing: 0.5px;
}
.tab-item.active {
  color: #800000;
  border-bottom-color: #800000;
}
.tab-item:hover {
  color: #800000;
}

/* MAIN CARD AREA */
.main-card {
  /* No extra styling needed, keeps layout clean */
}

/* GROUP MANAGER STYLES */
.sub-nav-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-bottom: 30px;
}
.btn-toggle {
  background: transparent;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 600;
  color: #94a3b8;
  cursor: pointer;
  transition: all 0.2s;
}
.btn-toggle.active {
  background: #f1f5f9;
  color: #800000;
  font-weight: 700;
}
.btn-toggle:hover {
  color: #800000;
}

/* CREATE GROUP FORM */
.section-title {
  color: #800000;
  font-size: 1.2rem;
  margin-bottom: 20px;
}
.create-form-grid {
  display: grid;
  grid-template-columns: 1fr 1.5fr 2fr 0.8fr;
  gap: 15px;
  margin-bottom: 40px;
  align-items: start;
}
.std-input {
  width: 100%;
  padding: 12px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  outline: none;
  font-size: 0.9rem;
}
.tags-row {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  margin-top: 8px;
}
.member-tag {
  background: #e2e8f0;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  display: flex;
  align-items: center;
  gap: 5px;
}
.remove-x {
  cursor: pointer;
  font-weight: bold;
  color: #64748b;
}
.remove-x:hover { color: #ef4444; }

.btn-primary {
  background: #E8B931; /* Gold/Yellow from designs */
  border: none;
  padding: 12px;
  border-radius: 8px;
  font-weight: 800;
  color: white;
  cursor: pointer;
  height: 44px; /* Align with inputs */
}
.btn-primary:hover { opacity: 0.9; }

/* STANDARD TABLE STYLES */
.table-wrapper {
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  overflow: hidden;
}
.std-table {
  width: 100%;
  border-collapse: collapse;
}
.std-table th {
  background: #f8fafc;
  padding: 15px;
  color: #334155;
  font-size: 14px;
  font-weight: 700;
  text-align: left;
  border-bottom: 1px solid #e2e8f0;
}
.std-table td {
  padding: 15px;
  border-bottom: 1px solid #f1f5f9;
  font-size: 14px;
  color: #333;
  vertical-align: middle;
}
.std-table .text-center { text-align: center; }
.std-table .text-left { text-align: left; }
.std-table .bold { font-weight: 700; }
.std-table .align-top { vertical-align: top; }
.std-table .pt-20 { padding-top: 20px; }
.std-table .p-0 { padding: 0 !important; }

.empty-msg {
  text-align: center;
  padding: 40px;
  color: #94a3b8;
  font-style: italic;
}
.stacked-text {
  display: flex;
  flex-direction: column;
  gap: 4px;
  font-size: 13px;
}

/* SUBMISSION INNER ROWS */
.research-row {
  display: flex;
  align-items: center;
  padding: 15px 20px;
  border-bottom: 1px solid #f1f5f9;
}
.research-row:last-child { border-bottom: none; }
.res-title { width: 45%; font-weight: 600; }
.res-status { width: 25%; text-align: center; }
.res-actions { width: 30%; display: flex; justify-content: flex-end; gap: 8px; }

.badge { padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 700; text-transform: uppercase; }
.badge.pending { background: #fff7ed; color: #9a3412; }
.badge.accepted { background: #dcfce7; color: #166534; }
.badge.rejected { background: #fee2e2; color: #991b1b; }

.btn-accept, .btn-reject {
  border: none; padding: 6px 12px; border-radius: 4px; color: white;
  font-size: 12px; font-weight: 600; cursor: pointer;
}
.btn-accept { background: #065f27; }
.btn-reject { background: #7f0000; }
.btn-accept:disabled, .btn-reject:disabled { opacity: 0.3; cursor: not-allowed; }

.info-banner {
  background: #f8fafc;
  padding: 15px;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  margin-bottom: 20px;
  font-size: 14px;
}
.info-banner .label { font-weight: 800; margin-right: 10px; }

/* ENROLLEE SECTION STYLES */
.top-bar-enrollee {
  display: flex;
  align-items: flex-end;
  gap: 15px;
  margin-bottom: 25px;
}
.search-group { flex-grow: 1; }
.search-group label {
  display: block; font-size: 13px; font-weight: 700; color: #64748b; margin-bottom: 8px;
}
.search-field {
  display: flex; align-items: center; background: #f8fafc;
  border: 1px solid #e2e8f0; border-radius: 8px; padding: 0 15px; height: 45px;
}
.search-field input { border: none; background: transparent; flex-grow: 1; outline: none; }
.btn-enroll {
  height: 45px; padding: 0 30px; background: #E8B931; color: white;
  border: none; border-radius: 8px; font-weight: 800; cursor: pointer; letter-spacing: 1px;
}
.stats-panel {
  display: flex; gap: 40px; margin-bottom: 20px; padding: 15px 25px;
  background: #f8fafc; border-radius: 8px; border: 1px solid #f1f5f9;
}
.stat .lbl { font-weight: 800; font-size: 14px; color: #333; margin-right: 8px; }
.stat .val { font-size: 14px; }

/* MODAL STYLES */
.modal-overlay {
  position: fixed; top: 0; left: 0; width: 100%; height: 100%;
  background: rgba(0, 0, 0, 0.5); display: flex; align-items: center; justify-content: center;
  z-index: 1000;
}
.modal-card {
  background: white; padding: 25px; border-radius: 12px; width: 420px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}
.text-green { color: #065f27; }
.text-red { color: #7f0000; }
.modal-input-group { margin: 20px 0; }
.modal-input-group label { display: block; font-weight: 700; font-size: 13px; margin-bottom: 8px; }
textarea {
  width: 100%; height: 100px; padding: 10px; border-radius: 6px;
  border: 1px solid #cbd5e1; resize: none; outline: none;
}
.modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
.btn-cancel { background: #e2e8f0; color: #475569; border: none; padding: 10px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; }
.btn-confirm-accept { background: #065f27; color: white; border: none; padding: 10px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; }
.btn-confirm-reject { background: #7f0000; color: white; border: none; padding: 10px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; }
</style>