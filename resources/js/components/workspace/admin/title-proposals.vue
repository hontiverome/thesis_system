<template>
  <div class="admin-proposals-container">
    <div class="header-section">
      <h1>Title Proposals - Admin View</h1>
      <p class="subtitle">Review and manage all thesis title proposals</p>
    </div>

    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
      <p>Loading proposals...</p>
    </div>

    <div v-else-if="error" class="error-state">
      <p>{{ error }}</p>
      <button @click="loadProposals" class="retry-btn">Retry</button>
    </div>

    <div v-else class="proposals-content">
      <!-- Filters -->
      <div class="filters-section">
        <div class="filter-group">
          <label>Status:</label>
          <select v-model="filters.status">
            <option value="">All</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>
        
        <div class="filter-group">
          <label>Search:</label>
          <input 
            v-model="filters.search" 
            type="text" 
            placeholder="Search by title or group..."
          />
        </div>
      </div>

      <!-- Proposals List -->
      <div v-if="filteredProposals.length === 0" class="empty-state">
        <p>No proposals found</p>
      </div>

      <div v-else class="proposals-grid">
        <div 
          v-for="proposal in filteredProposals" 
          :key="proposal.ProposalID"
          class="proposal-card"
        >
          <div class="card-header">
            <h3>{{ proposal.Title }}</h3>
            <span 
              class="status-badge" 
              :class="`status-${proposal.Status?.toLowerCase()}`"
            >
              {{ proposal.Status }}
            </span>
          </div>

          <div class="card-body">
            <div class="info-row">
              <strong>Group:</strong>
              <span>{{ proposal.GroupCode || 'N/A' }}</span>
            </div>
            
            <div class="info-row">
              <strong>Adviser:</strong>
              <span>{{ proposal.AdviserName || 'Unassigned' }}</span>
            </div>

            <div class="info-row">
              <strong>Submitted:</strong>
              <span>{{ formatDate(proposal.SubmissionDate) }}</span>
            </div>

            <div class="description">
              <strong>Description:</strong>
              <p>{{ proposal.Description || 'No description provided' }}</p>
            </div>
          </div>

          <div class="card-actions">
            <button @click="viewDetails(proposal)" class="btn-view">
              View Details
            </button>
            <button 
              v-if="proposal.Status === 'pending'" 
              @click="approveProposal(proposal)" 
              class="btn-approve"
            >
              Approve
            </button>
            <button 
              v-if="proposal.Status === 'pending'" 
              @click="rejectProposal(proposal)" 
              class="btn-reject"
            >
              Reject
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { adminApi } from '@/services';

const loading = ref(false);
const error = ref(null);
const proposals = ref([]);

const filters = ref({
  status: '',
  search: ''
});

const filteredProposals = computed(() => {
  let result = proposals.value;

  // Filter by status
  if (filters.value.status) {
    result = result.filter(p => 
      p.Status?.toLowerCase() === filters.value.status.toLowerCase()
    );
  }

  // Filter by search
  if (filters.value.search) {
    const search = filters.value.search.toLowerCase();
    result = result.filter(p => 
      p.Title?.toLowerCase().includes(search) ||
      p.GroupCode?.toLowerCase().includes(search) ||
      p.AdviserName?.toLowerCase().includes(search)
    );
  }

  return result;
});

const loadProposals = async () => {
  loading.value = true;
  error.value = null;
  
  try {
    // Use admin API to get all proposals
    const response = await adminApi.getProposals();
    proposals.value = response.data || [];
  } catch (err) {
    error.value = 'Failed to load proposals. Please try again.';
    console.error('Error loading proposals:', err);
  } finally {
    loading.value = false;
  }
};

const viewDetails = (proposal) => {
  // TODO: Implement proposal details modal/page
  console.log('View details:', proposal);
};

const approveProposal = async (proposal) => {
  if (!confirm(`Approve proposal "${proposal.Title}"?`)) return;
  
  try {
    await adminApi.updateProposalStatus(proposal.ProposalID, 'approved');
    await loadProposals(); // Reload
  } catch (err) {
    alert('Failed to approve proposal');
    console.error('Error approving proposal:', err);
  }
};

const rejectProposal = async (proposal) => {
  if (!confirm(`Reject proposal "${proposal.Title}"?`)) return;
  
  try {
    await adminApi.updateProposalStatus(proposal.ProposalID, 'rejected');
    await loadProposals(); // Reload
  } catch (err) {
    alert('Failed to reject proposal');
    console.error('Error rejecting proposal:', err);
  }
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

onMounted(() => {
  loadProposals();
});
</script>

<style scoped>
.admin-proposals-container {
  padding: 2rem;
  max-width: 1400px;
  margin: 0 auto;
}

.header-section {
  margin-bottom: 2rem;
}

.header-section h1 {
  color: var(--color-maroon);
  font-size: 2rem;
  margin-bottom: 0.5rem;
}

.subtitle {
  color: var(--color-text-secondary);
  font-size: 1rem;
}

.loading-state, .error-state {
  text-align: center;
  padding: 3rem;
}

.spinner {
  border: 3px solid var(--color-border);
  border-top-color: var(--color-maroon);
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
  margin: 0 auto 1rem;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.retry-btn {
  background: var(--color-maroon);
  color: white;
  border: none;
  padding: 0.5rem 1.5rem;
  border-radius: 4px;
  cursor: pointer;
  margin-top: 1rem;
}

.filters-section {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.filter-group label {
  font-weight: 600;
  font-size: 0.875rem;
  color: var(--color-text-primary);
}

.filter-group select,
.filter-group input {
  padding: 0.5rem;
  border: 1px solid var(--color-border);
  border-radius: 4px;
  min-width: 200px;
}

.empty-state {
  text-align: center;
  padding: 3rem;
  color: var(--color-text-secondary);
}

.proposals-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 1.5rem;
}

.proposal-card {
  border: 1px solid var(--color-border);
  border-radius: 8px;
  padding: 1.5rem;
  background: var(--color-bg-primary);
  transition: box-shadow 0.2s;
}

.proposal-card:hover {
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: start;
  margin-bottom: 1rem;
  gap: 1rem;
}

.card-header h3 {
  color: var(--color-text-primary);
  font-size: 1.125rem;
  margin: 0;
  flex: 1;
}

.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
}

.status-pending {
  background: #FFF3CD;
  color: #856404;
}

.status-approved {
  background: #D4EDDA;
  color: #155724;
}

.status-rejected {
  background: #F8D7DA;
  color: #721C24;
}

.card-body {
  margin-bottom: 1rem;
}

.info-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
}

.info-row strong {
  color: var(--color-text-secondary);
}

.description {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid var(--color-border);
}

.description strong {
  display: block;
  margin-bottom: 0.5rem;
  color: var(--color-text-secondary);
  font-size: 0.875rem;
}

.description p {
  font-size: 0.875rem;
  color: var(--color-text-primary);
  line-height: 1.5;
}

.card-actions {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.card-actions button {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.875rem;
  font-weight: 500;
  transition: opacity 0.2s;
}

.card-actions button:hover {
  opacity: 0.9;
}

.btn-view {
  background: var(--color-gray);
  color: white;
}

.btn-approve {
  background: #28A745;
  color: white;
}

.btn-reject {
  background: #DC3545;
  color: white;
}
</style>
