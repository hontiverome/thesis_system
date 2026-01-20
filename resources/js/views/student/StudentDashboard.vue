<template>
  <div class="page-view">
    <header class="page-header">
      <h1 class="page-title">Student Dashboard</h1>
      <p class="page-subtitle">Welcome back, {{ user?.FullName || 'Student' }}</p>
    </header>

    <!-- Loading State -->
    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
      <p>Loading your dashboard...</p>
    </div>

    <!-- Error State -->
    <div v-if="error" class="error-banner">
      <p>{{ error }}</p>
      <button @click="loadDashboard" class="btn-retry">Retry</button>
    </div>

    <!-- Dashboard Content -->
    <div v-if="!loading && !error" class="dashboard-grid">
      
      <!-- Group Information Card -->
      <div class="card">
        <h3 class="card-title">My Group</h3>
        <div v-if="groupInfo" class="group-info">
          <div class="info-row">
            <span class="label">Group Code:</span>
            <span class="value">{{ groupInfo.code }}</span>
          </div>
          <div class="info-row">
            <span class="label">Course:</span>
            <span class="value">{{ groupInfo.course }}</span>
          </div>
          <div class="info-row">
            <span class="label">Adviser:</span>
            <span class="value">{{ groupInfo.adviser }}</span>
          </div>
          <div class="info-row">
            <span class="label">Section:</span>
            <span class="value">{{ groupInfo.section }}</span>
          </div>
          <div class="info-row">
            <span class="label">Members:</span>
            <div class="members-list">
              <div v-for="member in groupInfo.members" :key="member.id" class="member-item">
                {{ member.name }}
                <span v-if="member.isLeader" class="badge-leader">Leader</span>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="empty-state">
          <p>You are not assigned to any group yet.</p>
        </div>
      </div>

      <!-- Proposals Card -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Thesis Proposals</h3>
          <button 
            v-if="isGroupLeader" 
            @click="showSubmitProposal = true" 
            class="btn-primary-sm"
          >
            + Submit New
          </button>
        </div>
        <div v-if="proposals.length > 0" class="proposals-list">
          <div v-for="proposal in proposals" :key="proposal.id" class="proposal-item">
            <div class="proposal-title">{{ proposal.title }}</div>
            <div class="proposal-meta">
              <span>Submitted: {{ formatDate(proposal.submissionDate) }}</span>
              <span :class="['badge-status', proposal.status.toLowerCase()]">
                {{ proposal.status }}
              </span>
            </div>
            <div v-if="proposal.feedback" class="proposal-feedback">
              <strong>Feedback:</strong> {{ proposal.feedback }}
            </div>
          </div>
        </div>
        <div v-else class="empty-state">
          <p>No proposals submitted yet.</p>
        </div>
      </div>

      <!-- Defense Information Card -->
      <div class="card">
        <h3 class="card-title">Defense Schedule</h3>
        <div v-if="defenseInfo" class="defense-info">
          <div class="info-row">
            <span class="label">Date:</span>
            <span class="value">{{ formatDate(defenseInfo.date) }}</span>
          </div>
          <div class="info-row">
            <span class="label">Time:</span>
            <span class="value">{{ defenseInfo.time }}</span>
          </div>
          <div class="info-row">
            <span class="label">Venue:</span>
            <span class="value">{{ defenseInfo.venue }}</span>
          </div>
          <div class="info-row">
            <span class="label">Status:</span>
            <span :class="['badge-status', defenseInfo.status.toLowerCase()]">
              {{ defenseInfo.status }}
            </span>
          </div>
        </div>
        <div v-else class="empty-state">
          <p>No defense scheduled yet.</p>
        </div>
      </div>

      <!-- Panel Invitations Card -->
      <div class="card">
        <h3 class="card-title">Panel Members</h3>
        <div v-if="panelInvitations.length > 0" class="panel-list">
          <div v-for="panel in panelInvitations" :key="panel.id" class="panel-item">
            <span class="panel-name">{{ panel.facultyName }}</span>
            <span :class="['badge-status', panel.status.toLowerCase()]">
              {{ panel.status }}
            </span>
          </div>
        </div>
        <div v-else class="empty-state">
          <p>No panel members assigned yet.</p>
        </div>
      </div>

    </div>

    <!-- Submit Proposal Modal -->
    <div v-if="showSubmitProposal" class="modal-overlay" @click.self="showSubmitProposal = false">
      <div class="modal-card">
        <h3>Submit Thesis Proposal</h3>
        <form @submit.prevent="submitProposal">
          <div class="form-group">
            <label>Research Title *</label>
            <input v-model="proposalForm.title" type="text" class="form-input" required />
          </div>
          <div class="form-group">
            <label>Abstract *</label>
            <textarea v-model="proposalForm.abstract" class="form-textarea" rows="4" required></textarea>
          </div>
          <div class="form-group">
            <label>Upload Document (PDF) *</label>
            <input type="file" @change="handleFileUpload" accept=".pdf" required />
          </div>
          <div class="modal-footer">
            <button type="button" @click="showSubmitProposal = false" class="btn-cancel">
              Cancel
            </button>
            <button type="submit" class="btn-primary" :disabled="submitting">
              {{ submitting ? 'Submitting...' : 'Submit' }}
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { studentApi } from '@/services';
import { useAuth } from '@/composables/useAuth';

const { user, isAuthenticated } = useAuth();

// State
const loading = ref(false);
const error = ref(null);
const groupInfo = ref(null);
const proposals = ref([]);
const defenseInfo = ref(null);
const panelInvitations = ref([]);
const showSubmitProposal = ref(false);
const submitting = ref(false);

// Form
const proposalForm = ref({
  title: '',
  abstract: '',
  file: null
});

// Computed
const isGroupLeader = computed(() => {
  return groupInfo.value?.members?.some(m => m.isLeader && m.id === user.value?.UserID);
});

// Load Dashboard Data
const loadDashboard = async () => {
  try {
    loading.value = true;
    error.value = null;

    // Load all data in parallel
    const [groupResponse, facultyResponse, invitationsResponse] = await Promise.all([
      studentApi.getGroupInfo().catch(err => ({ data: null })),
      studentApi.getAllFaculty().catch(err => ({ data: [] })),
      studentApi.getPanelInvitations().catch(err => ({ data: [] }))
    ]);

    groupInfo.value = groupResponse.data;
    panelInvitations.value = invitationsResponse.data;

    // If group exists, load group-specific data
    if (groupInfo.value?.id) {
      const [proposalsResponse, defenseResponse] = await Promise.all([
        studentApi.getProposals(groupInfo.value.id).catch(err => ({ data: [] })),
        studentApi.getDefenseVerdict(groupInfo.value.id).catch(err => ({ data: null }))
      ]);

      proposals.value = proposalsResponse.data;
      defenseInfo.value = defenseResponse.data;
    }

  } catch (err) {
    error.value = 'Failed to load dashboard data. Please try again.';
    console.error('Dashboard load error:', err);
  } finally {
    loading.value = false;
  }
};

// Submit Proposal
const handleFileUpload = (event) => {
  proposalForm.value.file = event.target.files[0];
};

const submitProposal = async () => {
  if (!groupInfo.value?.id) {
    alert('You must be part of a group to submit a proposal');
    return;
  }

  try {
    submitting.value = true;
    
    const formData = new FormData();
    formData.append('title', proposalForm.value.title);
    formData.append('abstract', proposalForm.value.abstract);
    formData.append('file', proposalForm.value.file);

    await studentApi.submitProposal(groupInfo.value.id, formData);

    // Reset form and reload proposals
    proposalForm.value = { title: '', abstract: '', file: null };
    showSubmitProposal.value = false;
    await loadDashboard();
    
    alert('Proposal submitted successfully!');
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to submit proposal');
    console.error('Proposal submission error:', err);
  } finally {
    submitting.value = false;
  }
};

// Utility
const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  });
};

// Initialize
onMounted(() => {
  if (isAuthenticated.value) {
    loadDashboard();
  }
});
</script>

<style scoped>
.page-view {
  padding: 20px;
  max-width: 1200px;
  margin: 0 auto;
}

.page-header {
  margin-bottom: 30px;
}

.page-title {
  font-size: 28px;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 8px;
}

.page-subtitle {
  font-size: 16px;
  color: #64748b;
}

.loading-state {
  text-align: center;
  padding: 60px 20px;
}

.spinner {
  width: 50px;
  height: 50px;
  border: 4px solid #f3f4f6;
  border-top-color: #E8B931;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 20px;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.error-banner {
  background: #fee2e2;
  border: 1px solid #fecaca;
  color: #991b1b;
  padding: 15px;
  border-radius: 8px;
  margin-bottom: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.btn-retry {
  background: #991b1b;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  cursor: pointer;
}

.dashboard-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
}

.card {
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.card-title {
  font-size: 18px;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 15px;
}

.btn-primary-sm {
  background: #E8B931;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
}

.info-row {
  display: flex;
  justify-content: space-between;
  padding: 10px 0;
  border-bottom: 1px solid #f1f5f9;
}

.info-row:last-child {
  border-bottom: none;
}

.label {
  font-weight: 600;
  color: #64748b;
}

.value {
  color: #1e293b;
}

.members-list {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.member-item {
  display: flex;
  align-items: center;
  gap: 8px;
}

.badge-leader {
  background: #E8B931;
  color: white;
  padding: 2px 8px;
  border-radius: 4px;
  font-size: 11px;
  font-weight: 600;
}

.proposals-list, .panel-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.proposal-item {
  padding: 15px;
  background: #f8fafc;
  border-radius: 8px;
  border-left: 3px solid #E8B931;
}

.proposal-title {
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 8px;
}

.proposal-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 13px;
  color: #64748b;
}

.proposal-feedback {
  margin-top: 10px;
  padding: 10px;
  background: white;
  border-radius: 6px;
  font-size: 13px;
}

.badge-status {
  padding: 4px 10px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
}

.badge-status.pending {
  background: #fff7ed;
  color: #9a3412;
}

.badge-status.approved, .badge-status.accepted {
  background: #dcfce7;
  color: #166534;
}

.badge-status.rejected {
  background: #fee2e2;
  color: #991b1b;
}

.panel-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
  background: #f8fafc;
  border-radius: 6px;
}

.empty-state {
  text-align: center;
  padding: 40px 20px;
  color: #94a3b8;
  font-style: italic;
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-card {
  background: white;
  padding: 30px;
  border-radius: 12px;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  font-weight: 600;
  margin-bottom: 8px;
  color: #1e293b;
}

.form-input, .form-textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  font-size: 14px;
  outline: none;
}

.form-input:focus, .form-textarea:focus {
  border-color: #E8B931;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 20px;
}

.btn-cancel {
  background: #e2e8f0;
  color: #475569;
  border: none;
  padding: 10px 20px;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
}

.btn-primary {
  background: #E8B931;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
}

.btn-primary:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
