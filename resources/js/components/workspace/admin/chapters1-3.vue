<template>
  <div class="chapters-container">
    <div class="header-section">
      <h1>Chapters 1-3 - Admin View</h1>
      <p class="subtitle">Review submitted chapters from all groups</p>
    </div>

    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
      <p>Loading submissions...</p>
    </div>

    <div v-else-if="error" class="error-state">
      <p>{{ error }}</p>
      <button @click="loadSubmissions" class="retry-btn">Retry</button>
    </div>

    <div v-else class="submissions-content">
      <!-- Filter Controls -->
      <div class="filter-section">
        <select v-model="filterStatus">
          <option value="">All Status</option>
          <option value="pending">Pending Review</option>
          <option value="approved">Approved</option>
          <option value="needs-revision">Needs Revision</option>
        </select>

        <input 
          v-model="searchQuery" 
          type="text" 
          placeholder="Search by group or title..."
          class="search-input"
        />
      </div>

      <!-- Submissions List -->
      <div v-if="filteredSubmissions.length === 0" class="empty-state">
        <p>No submissions found</p>
      </div>

      <div v-else class="submissions-list">
        <div 
          v-for="submission in filteredSubmissions" 
          :key="submission.SubmissionID"
          class="submission-card"
        >
          <div class="card-header">
            <div>
              <h3>{{ submission.Title }}</h3>
              <p class="group-code">{{ submission.GroupCode }}</p>
            </div>
            <span 
              class="status-badge"
              :class="`status-${submission.Status?.toLowerCase().replace(' ', '-')}`"
            >
              {{ submission.Status }}
            </span>
          </div>

          <div class="card-body">
            <div class="info-grid">
              <div class="info-item">
                <strong>Submitted By:</strong>
                <span>{{ submission.SubmittedBy }}</span>
              </div>
              
              <div class="info-item">
                <strong>Submission Date:</strong>
                <span>{{ formatDate(submission.SubmissionDate) }}</span>
              </div>

              <div class="info-item">
                <strong>Adviser:</strong>
                <span>{{ submission.Adviser || 'N/A' }}</span>
              </div>

              <div class="info-item">
                <strong>Chapter:</strong>
                <span>{{ submission.ChapterNumber }}</span>
              </div>
            </div>

            <div v-if="submission.Comments" class="comments-section">
              <strong>Comments:</strong>
              <p>{{ submission.Comments }}</p>
            </div>
          </div>

          <div class="card-actions">
            <button 
              @click="downloadFile(submission)" 
              class="btn-download"
            >
              📥 Download
            </button>
            <button 
              @click="viewSubmission(submission)" 
              class="btn-view"
            >
              View Details
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
const submissions = ref([]);
const filterStatus = ref('');
const searchQuery = ref('');

const filteredSubmissions = computed(() => {
  let result = submissions.value;

  // Filter by status
  if (filterStatus.value) {
    result = result.filter(s => 
      s.Status?.toLowerCase() === filterStatus.value.toLowerCase()
    );
  }

  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(s => 
      s.Title?.toLowerCase().includes(query) ||
      s.GroupCode?.toLowerCase().includes(query)
    );
  }

  return result;
});

const loadSubmissions = async () => {
  loading.value = true;
  error.value = null;
  
  try {
    // Use admin API to get all submissions
    const response = await adminApi.getSubmissions();
    submissions.value = response.data || [];
  } catch (err) {
    error.value = 'Failed to load submissions. Please try again.';
    console.error('Error loading submissions:', err);
  } finally {
    loading.value = false;
  }
};

const downloadFile = (submission) => {
  // TODO: Implement file download
  console.log('Download file:', submission);
};

const viewSubmission = (submission) => {
  // TODO: Implement submission details view
  console.log('View submission:', submission);
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
  loadSubmissions();
});
</script>

<style scoped>
.chapters-container {
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

.filter-section {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
}

.filter-section select,
.search-input {
  padding: 0.75rem;
  border: 1px solid var(--color-border);
  border-radius: 4px;
  font-size: 1rem;
}

.search-input {
  flex: 1;
  min-width: 300px;
}

.empty-state {
  text-align: center;
  padding: 3rem;
  color: var(--color-text-secondary);
}

.submissions-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.submission-card {
  border: 1px solid var(--color-border);
  border-radius: 8px;
  padding: 1.5rem;
  background: var(--color-bg-primary);
  transition: box-shadow 0.2s;
}

.submission-card:hover {
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
  font-size: 1.25rem;
  margin: 0 0 0.25rem 0;
}

.group-code {
  color: var(--color-text-secondary);
  font-size: 0.875rem;
  margin: 0;
}

.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  white-space: nowrap;
}

.status-pending {
  background: #FFF3CD;
  color: #856404;
}

.status-approved {
  background: #D4EDDA;
  color: #155724;
}

.status-needs-revision {
  background: #F8D7DA;
  color: #721C24;
}

.card-body {
  margin-bottom: 1rem;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: 1rem;
}

.info-item {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
  font-size: 0.875rem;
}

.info-item strong {
  color: var(--color-text-secondary);
}

.comments-section {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid var(--color-border);
}

.comments-section strong {
  display: block;
  margin-bottom: 0.5rem;
  color: var(--color-text-secondary);
  font-size: 0.875rem;
}

.comments-section p {
  font-size: 0.875rem;
  color: var(--color-text-primary);
  margin: 0;
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

.btn-download {
  background: var(--color-gold);
  color: var(--color-text-primary);
}

.btn-view {
  background: var(--color-maroon);
  color: white;
}
</style>
