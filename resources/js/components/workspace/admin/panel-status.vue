<template>
  <div class="panel-status-container">
    <div class="header-section">
      <h1>Panel Status - Admin View</h1>
      <p class="subtitle">Monitor defense panel assignments and schedules</p>
    </div>

    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
      <p>Loading panel information...</p>
    </div>

    <div v-else-if="error" class="error-state">
      <p>{{ error }}</p>
      <button @click="loadPanels" class="retry-btn">Retry</button>
    </div>

    <div v-else class="panels-content">
      <!-- Stats Overview -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-value">{{ stats.total }}</div>
          <div class="stat-label">Total Panels</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">{{ stats.scheduled }}</div>
          <div class="stat-label">Scheduled</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">{{ stats.completed }}</div>
          <div class="stat-label">Completed</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">{{ stats.pending }}</div>
          <div class="stat-label">Pending</div>
        </div>
      </div>

      <!-- Panels List -->
      <div class="panels-section">
        <h2>Defense Panels</h2>
        
        <div v-if="panels.length === 0" class="empty-state">
          <p>No defense panels found</p>
        </div>

        <div v-else class="panels-table">
          <table>
            <thead>
              <tr>
                <th>Group</th>
                <th>Defense Type</th>
                <th>Panel Members</th>
                <th>Schedule</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="panel in panels" :key="panel.DefenseID">
                <td>{{ panel.GroupCode }}</td>
                <td>{{ panel.DefenseType }}</td>
                <td>
                  <div class="panel-members">
                    <div v-for="member in panel.Members" :key="member.UserID">
                      {{ member.Name }} ({{ member.Role }})
                    </div>
                  </div>
                </td>
                <td>{{ formatDateTime(panel.ScheduledDateTime) }}</td>
                <td>
                  <span 
                    class="status-badge"
                    :class="`status-${panel.Status?.toLowerCase()}`"
                  >
                    {{ panel.Status }}
                  </span>
                </td>
                <td>
                  <button @click="viewPanel(panel)" class="btn-action">
                    View
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
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
const panels = ref([]);

const stats = computed(() => {
  const total = panels.value.length;
  const scheduled = panels.value.filter(p => p.Status === 'scheduled').length;
  const completed = panels.value.filter(p => p.Status === 'completed').length;
  const pending = panels.value.filter(p => p.Status === 'pending').length;
  
  return { total, scheduled, completed, pending };
});

const loadPanels = async () => {
  loading.value = true;
  error.value = null;
  
  try {
    // Use admin API to get all defense panels
    const response = await adminApi.getDefensePanels();
    panels.value = response.data || [];
  } catch (err) {
    error.value = 'Failed to load panel information. Please try again.';
    console.error('Error loading panels:', err);
  } finally {
    loading.value = false;
  }
};

const viewPanel = (panel) => {
  // TODO: Implement panel details modal/page
  console.log('View panel:', panel);
};

const formatDateTime = (dateString) => {
  if (!dateString) return 'Not scheduled';
  return new Date(dateString).toLocaleString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

onMounted(() => {
  loadPanels();
});
</script>

<style scoped>
.panel-status-container {
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

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}

.stat-card {
  background: var(--color-bg-primary);
  border: 1px solid var(--color-border);
  border-radius: 8px;
  padding: 1.5rem;
  text-align: center;
}

.stat-value {
  font-size: 2.5rem;
  font-weight: bold;
  color: var(--color-maroon);
  margin-bottom: 0.5rem;
}

.stat-label {
  color: var(--color-text-secondary);
  font-size: 0.875rem;
}

.panels-section h2 {
  color: var(--color-text-primary);
  margin-bottom: 1rem;
}

.empty-state {
  text-align: center;
  padding: 3rem;
  color: var(--color-text-secondary);
}

.panels-table {
  background: var(--color-bg-primary);
  border: 1px solid var(--color-border);
  border-radius: 8px;
  overflow: hidden;
}

table {
  width: 100%;
  border-collapse: collapse;
}

thead {
  background: var(--color-bg-secondary);
}

th {
  padding: 1rem;
  text-align: left;
  font-weight: 600;
  color: var(--color-text-primary);
  border-bottom: 2px solid var(--color-border);
}

td {
  padding: 1rem;
  border-bottom: 1px solid var(--color-border);
  color: var(--color-text-primary);
}

tr:last-child td {
  border-bottom: none;
}

.panel-members {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
  font-size: 0.875rem;
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

.status-scheduled {
  background: #D1ECF1;
  color: #0C5460;
}

.status-completed {
  background: #D4EDDA;
  color: #155724;
}

.btn-action {
  background: var(--color-maroon);
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.875rem;
  transition: opacity 0.2s;
}

.btn-action:hover {
  opacity: 0.9;
}
</style>
