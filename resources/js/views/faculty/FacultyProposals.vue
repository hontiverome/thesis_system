<template>
  <div class="page-view">
    <header class="page-header">
      <h1 class="page-title">Thesis Proposals</h1>
      <p class="page-subtitle">Review and evaluate submitted proposals</p>
    </header>

    <!-- Filters -->
    <div class="filters-bar">
      <select v-model="statusFilter" class="filter-select">
        <option value="all">All Status</option>
        <option value="pending">Pending</option>
        <option value="approved">Approved</option>
        <option value="rejected">Rejected</option>
      </select>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
      <p>Loading proposals...</p>
    </div>

    <!-- Proposals Table -->
    <div v-if="!loading && filteredProposals.length > 0" class="table-container">
      <table class="proposals-table">
        <thead>
          <tr>
            <th>Group Code</th>
            <th>Research Title</th>
            <th>Submission Date</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="proposal in filteredProposals" :key="proposal.id">
            <td>{{ proposal.groupCode }}</td>
            <td class="title-cell">{{ proposal.title }}</td>
            <td>{{ formatDate(proposal.submissionDate) }}</td>
            <td>
              <span :class="['badge-status', proposal.status.toLowerCase()]">
                {{ proposal.status }}
              </span>
            </td>
            <td class="actions-cell">
              <button @click="viewProposal(proposal)" class="btn-view">
                View
              </button>
              <button 
                v-if="proposal.status === 'pending'" 
                @click="openReviewModal(proposal)" 
                class="btn-review"
              >
                Review
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Empty State -->
    <div v-if="!loading && filteredProposals.length === 0" class="empty-state">
      <p>No proposals found.</p>
    </div>

    <!-- Review Modal -->
    <div v-if="reviewModal" class="modal-overlay" @click.self="closeReviewModal">
      <div class="modal-card modal-lg">
        <h3>Review Proposal</h3>
        
        <div class="proposal-details">
          <div class="detail-row">
            <span class="detail-label">Group Code:</span>
            <span>{{ reviewModal.groupCode }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Title:</span>
            <span>{{ reviewModal.title }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Abstract:</span>
            <p class="abstract-text">{{ reviewModal.abstract }}</p>
          </div>
        </div>

        <form @submit.prevent="submitReview">
          <div class="form-group">
            <label>Decision *</label>
            <div class="radio-group">
              <label class="radio-label">
                <input type="radio" v-model="reviewForm.verdict" value="approved" required />
                <span>Approve</span>
              </label>
              <label class="radio-label">
                <input type="radio" v-model="reviewForm.verdict" value="rejected" required />
                <span>Reject</span>
              </label>
            </div>
          </div>

          <div class="form-group">
            <label>Comments *</label>
            <textarea 
              v-model="reviewForm.comments" 
              class="form-textarea" 
              rows="4" 
              placeholder="Provide feedback or recommendations..."
              required
            ></textarea>
          </div>

          <div class="modal-footer">
            <button type="button" @click="closeReviewModal" class="btn-cancel">
              Cancel
            </button>
            <button type="submit" class="btn-primary" :disabled="submitting">
              {{ submitting ? 'Submitting...' : 'Submit Review' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- View Proposal Modal -->
    <div v-if="viewModal" class="modal-overlay" @click.self="viewModal = null">
      <div class="modal-card modal-lg">
        <h3>Proposal Details</h3>
        
        <div class="proposal-details">
          <div class="detail-row">
            <span class="detail-label">Group Code:</span>
            <span>{{ viewModal.groupCode }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Title:</span>
            <span>{{ viewModal.title }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Submission Date:</span>
            <span>{{ formatDate(viewModal.submissionDate) }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Status:</span>
            <span :class="['badge-status', viewModal.status.toLowerCase()]">
              {{ viewModal.status }}
            </span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Abstract:</span>
            <p class="abstract-text">{{ viewModal.abstract }}</p>
          </div>
          <div v-if="viewModal.feedback" class="detail-row">
            <span class="detail-label">Feedback:</span>
            <p class="abstract-text">{{ viewModal.feedback }}</p>
          </div>
        </div>

        <div class="modal-footer">
          <button @click="viewModal = null" class="btn-cancel">Close</button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { facultyApi } from '@/services';

// State
const loading = ref(false);
const proposals = ref([]);
const statusFilter = ref('all');
const reviewModal = ref(null);
const viewModal = ref(null);
const submitting = ref(false);

// Form
const reviewForm = ref({
  verdict: '',
  comments: ''
});

// Computed
const filteredProposals = computed(() => {
  if (statusFilter.value === 'all') return proposals.value;
  return proposals.value.filter(p => p.status.toLowerCase() === statusFilter.value);
});

// Load Proposals
const loadProposals = async () => {
  try {
    loading.value = true;
    const response = await facultyApi.getProposals();
    proposals.value = response.data || [];
  } catch (err) {
    console.error('Failed to load proposals:', err);
    alert('Failed to load proposals. Please try again.');
  } finally {
    loading.value = false;
  }
};

// View Proposal
const viewProposal = (proposal) => {
  viewModal.value = proposal;
};

// Open Review Modal
const openReviewModal = (proposal) => {
  reviewModal.value = proposal;
  reviewForm.value = { verdict: '', comments: '' };
};

// Close Review Modal
const closeReviewModal = () => {
  reviewModal.value = null;
  reviewForm.value = { verdict: '', comments: '' };
};

// Submit Review
const submitReview = async () => {
  try {
    submitting.value = true;
    
    await facultyApi.updateProposalVerdict(reviewModal.value.id, {
      verdict: reviewForm.value.verdict,
      comments: reviewForm.value.comments
    });

    // Reload proposals
    await loadProposals();
    closeReviewModal();
    
    alert('Review submitted successfully!');
  } catch (err) {
    console.error('Failed to submit review:', err);
    alert(err.response?.data?.message || 'Failed to submit review. Please try again.');
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
  loadProposals();
});
</script>

<style scoped>
.page-view {
  padding: 20px;
  max-width: 1400px;
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

.filters-bar {
  margin-bottom: 20px;
  display: flex;
  gap: 15px;
}

.filter-select {
  padding: 10px 15px;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  font-size: 14px;
  background: white;
  cursor: pointer;
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

.table-container {
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.proposals-table {
  width: 100%;
  border-collapse: collapse;
}

.proposals-table thead {
  background: #f8fafc;
}

.proposals-table th {
  text-align: left;
  padding: 15px;
  font-weight: 700;
  color: #1e293b;
  border-bottom: 2px solid #e2e8f0;
}

.proposals-table td {
  padding: 15px;
  border-bottom: 1px solid #f1f5f9;
  color: #475569;
}

.title-cell {
  font-weight: 600;
  color: #1e293b;
  max-width: 300px;
}

.actions-cell {
  display: flex;
  gap: 8px;
}

.btn-view {
  background: #e2e8f0;
  color: #475569;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
}

.btn-review {
  background: #E8B931;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
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

.badge-status.approved {
  background: #dcfce7;
  color: #166534;
}

.badge-status.rejected {
  background: #fee2e2;
  color: #991b1b;
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
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
  max-height: 90vh;
  overflow-y: auto;
}

.modal-lg {
  max-width: 700px;
}

.proposal-details {
  margin: 20px 0;
  padding: 20px;
  background: #f8fafc;
  border-radius: 8px;
}

.detail-row {
  margin-bottom: 15px;
}

.detail-label {
  display: block;
  font-weight: 700;
  color: #475569;
  margin-bottom: 5px;
}

.abstract-text {
  color: #1e293b;
  line-height: 1.6;
  margin-top: 5px;
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

.radio-group {
  display: flex;
  gap: 20px;
}

.radio-label {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
}

.radio-label input[type="radio"] {
  cursor: pointer;
}

.form-textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  font-size: 14px;
  font-family: inherit;
  outline: none;
  resize: vertical;
}

.form-textarea:focus {
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
