<template>
  <div class="faculty-management-container">
    <div class="header-section">
      <h1>Faculty Management</h1>
      <p class="subtitle">Manage faculty members and their assignments</p>
      <button @click="showAddModal = true" class="btn-add">
        + Add Faculty
      </button>
    </div>

    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
      <p>Loading faculty...</p>
    </div>

    <div v-else-if="error" class="error-state">
      <p>{{ error }}</p>
      <button @click="loadFaculty" class="retry-btn">Retry</button>
    </div>

    <div v-else class="faculty-content">
      <!-- Search and Filter -->
      <div class="controls-section">
        <input 
          v-model="searchQuery" 
          type="text" 
          placeholder="Search faculty by name or school ID..."
          class="search-input"
        />
        
        <select v-model="filterRole" class="filter-select">
          <option value="">All Roles</option>
          <option value="faculty">Faculty</option>
          <option value="adviser">Adviser</option>
        </select>
      </div>

      <!-- Faculty Grid -->
      <div v-if="filteredFaculty.length === 0" class="empty-state">
        <p>No faculty members found</p>
      </div>

      <div v-else class="faculty-grid">
        <div 
          v-for="faculty in filteredFaculty" 
          :key="faculty.UserID"
          class="faculty-card"
        >
          <div class="card-header">
            <div class="faculty-avatar">
              {{ getInitials(faculty.FirstName, faculty.LastName) }}
            </div>
            <div class="faculty-info">
              <h3>{{ faculty.FirstName }} {{ faculty.LastName }}</h3>
              <p class="school-id">{{ faculty.SchoolID }}</p>
              <div class="roles">
                <span 
                  v-for="role in faculty.Roles" 
                  :key="role"
                  class="role-badge"
                >
                  {{ role }}
                </span>
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="info-item">
              <strong>Email:</strong>
              <span>{{ faculty.Email }}</span>
            </div>
            
            <div class="info-item">
              <strong>Department:</strong>
              <span>{{ faculty.Department || 'N/A' }}</span>
            </div>

            <div v-if="faculty.GroupsAdvising" class="info-item">
              <strong>Advising Groups:</strong>
              <span>{{ faculty.GroupsAdvising }}</span>
            </div>
          </div>

          <div class="card-actions">
            <button @click="editFaculty(faculty)" class="btn-edit">
              Edit
            </button>
            <button @click="viewGroups(faculty)" class="btn-view">
              View Groups
            </button>
            <button 
              @click="removeFaculty(faculty)" 
              class="btn-remove"
            >
              Remove
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Add/Edit Modal (placeholder) -->
    <div v-if="showAddModal" class="modal-overlay" @click="showAddModal = false">
      <div class="modal-content" @click.stop>
        <h2>Add Faculty Member</h2>
        <p>Faculty creation form will be implemented here</p>
        <button @click="showAddModal = false" class="btn-close">Close</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { adminApi } from '@/services';

const loading = ref(false);
const error = ref(null);
const faculty = ref([]);
const searchQuery = ref('');
const filterRole = ref('');
const showAddModal = ref(false);

const filteredFaculty = computed(() => {
  let result = faculty.value;

  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(f => 
      f.FirstName?.toLowerCase().includes(query) ||
      f.LastName?.toLowerCase().includes(query) ||
      f.SchoolID?.toLowerCase().includes(query) ||
      f.Email?.toLowerCase().includes(query)
    );
  }

  // Filter by role
  if (filterRole.value) {
    result = result.filter(f => 
      f.Roles?.some(role => role.toLowerCase() === filterRole.value.toLowerCase())
    );
  }

  return result;
});

const loadFaculty = async () => {
  loading.value = true;
  error.value = null;
  
  try {
    // Get all users and filter faculty
    const response = await adminApi.getUsers();
    const allUsers = response.data || [];
    
    // Filter for faculty/adviser roles
    faculty.value = allUsers.filter(user => 
      user.Roles?.some(role => 
        role.toLowerCase() === 'faculty' || 
        role.toLowerCase() === 'adviser'
      )
    );
  } catch (err) {
    error.value = 'Failed to load faculty members. Please try again.';
    console.error('Error loading faculty:', err);
  } finally {
    loading.value = false;
  }
};

const getInitials = (firstName, lastName) => {
  const first = firstName?.charAt(0) || '';
  const last = lastName?.charAt(0) || '';
  return (first + last).toUpperCase() || 'FA';
};

const editFaculty = (facultyMember) => {
  // TODO: Implement edit functionality
  console.log('Edit faculty:', facultyMember);
};

const viewGroups = (facultyMember) => {
  // TODO: Implement view groups functionality
  console.log('View groups for:', facultyMember);
};

const removeFaculty = async (facultyMember) => {
  if (!confirm(`Remove ${facultyMember.FirstName} ${facultyMember.LastName}?`)) return;
  
  try {
    await adminApi.deleteUser(facultyMember.UserID);
    await loadFaculty(); // Reload
  } catch (err) {
    alert('Failed to remove faculty member');
    console.error('Error removing faculty:', err);
  }
};

onMounted(() => {
  loadFaculty();
});
</script>

<style scoped>
.faculty-management-container {
  padding: 2rem;
  max-width: 1400px;
  margin: 0 auto;
}

.header-section {
  display: flex;
  justify-content: space-between;
  align-items: start;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  gap: 1rem;
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

.btn-add {
  background: var(--color-maroon);
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 600;
  transition: opacity 0.2s;
}

.btn-add:hover {
  opacity: 0.9;
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

.controls-section {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
}

.search-input {
  flex: 1;
  min-width: 300px;
  padding: 0.75rem;
  border: 1px solid var(--color-border);
  border-radius: 4px;
  font-size: 1rem;
}

.filter-select {
  padding: 0.75rem;
  border: 1px solid var(--color-border);
  border-radius: 4px;
  font-size: 1rem;
  min-width: 150px;
}

.empty-state {
  text-align: center;
  padding: 3rem;
  color: var(--color-text-secondary);
}

.faculty-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 1.5rem;
}

.faculty-card {
  border: 1px solid var(--color-border);
  border-radius: 8px;
  padding: 1.5rem;
  background: var(--color-bg-primary);
  transition: box-shadow 0.2s;
}

.faculty-card:hover {
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.card-header {
  display: flex;
  gap: 1rem;
  margin-bottom: 1rem;
}

.faculty-avatar {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: var(--color-maroon);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  font-weight: bold;
  flex-shrink: 0;
}

.faculty-info {
  flex: 1;
}

.faculty-info h3 {
  margin: 0;
  color: var(--color-text-primary);
  font-size: 1.125rem;
}

.school-id {
  color: var(--color-text-secondary);
  font-size: 0.875rem;
  margin: 0.25rem 0;
}

.roles {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
  margin-top: 0.5rem;
}

.role-badge {
  padding: 0.25rem 0.75rem;
  background: var(--color-gold);
  color: var(--color-text-primary);
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 600;
}

.card-body {
  margin-bottom: 1rem;
}

.info-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
}

.info-item strong {
  color: var(--color-text-secondary);
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

.btn-edit {
  background: var(--color-gray);
  color: white;
}

.btn-view {
  background: var(--color-maroon);
  color: white;
}

.btn-remove {
  background: #DC3545;
  color: white;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: var(--color-bg-primary);
  padding: 2rem;
  border-radius: 8px;
  max-width: 500px;
  width: 90%;
}

.modal-content h2 {
  margin-top: 0;
  color: var(--color-maroon);
}

.btn-close {
  background: var(--color-gray);
  color: white;
  border: none;
  padding: 0.5rem 1.5rem;
  border-radius: 4px;
  cursor: pointer;
  margin-top: 1rem;
}
</style>
