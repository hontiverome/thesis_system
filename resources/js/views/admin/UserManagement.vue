<template>
  <div class="page-view">
    <header class="page-header">
      <div class="header-left">
        <h1 class="page-title">User Management</h1>
        <p class="page-subtitle">Manage users, roles, and permissions</p>
      </div>
      <button @click="showCreateUser = true" class="btn-primary">
        + Create User
      </button>
    </header>

    <!-- Filters -->
    <div class="filters-bar">
      <select v-model="roleFilter" class="filter-select">
        <option value="all">All Roles</option>
        <option value="admin">Admin</option>
        <option value="adviser">Adviser</option>
        <option value="faculty">Faculty</option>
        <option value="student">Student</option>
      </select>
      <input 
        v-model="searchQuery" 
        type="text" 
        class="search-input" 
        placeholder="Search by name or email..."
      />
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
      <p>Loading users...</p>
    </div>

    <!-- Users Table -->
    <div v-if="!loading && filteredUsers.length > 0" class="table-container">
      <table class="users-table">
        <thead>
          <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in filteredUsers" :key="user.id">
            <td>{{ user.userNumber }}</td>
            <td class="name-cell">{{ user.fullName }}</td>
            <td>{{ user.email }}</td>
            <td>
              <span :class="['badge-role', user.role.toLowerCase()]">
                {{ user.role }}
              </span>
            </td>
            <td>
              <span :class="['badge-status', user.status.toLowerCase()]">
                {{ user.status }}
              </span>
            </td>
            <td class="actions-cell">
              <button @click="openEditUser(user)" class="btn-edit">
                Edit
              </button>
              <button @click="openChangeRole(user)" class="btn-role">
                Change Role
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Empty State -->
    <div v-if="!loading && filteredUsers.length === 0" class="empty-state">
      <p>No users found.</p>
    </div>

    <!-- Create User Modal -->
    <div v-if="showCreateUser" class="modal-overlay" @click.self="showCreateUser = false">
      <div class="modal-card">
        <h3>Create New User</h3>
        <form @submit.prevent="createUser">
          <div class="form-group">
            <label>User Type *</label>
            <select v-model="userForm.type" class="form-select" required>
              <option value="">Select type</option>
              <option value="faculty">Faculty</option>
              <option value="student">Student</option>
            </select>
          </div>

          <div v-if="userForm.type === 'faculty'" class="form-group">
            <label>Rank *</label>
            <select v-model="userForm.rank" class="form-select" required>
              <option value="">Select rank</option>
              <option value="Professor">Professor</option>
              <option value="Associate Professor">Associate Professor</option>
              <option value="Assistant Professor">Assistant Professor</option>
              <option value="Instructor">Instructor</option>
            </select>
          </div>

          <div class="form-group">
            <label>First Name *</label>
            <input v-model="userForm.firstName" type="text" class="form-input" required />
          </div>

          <div class="form-group">
            <label>Last Name *</label>
            <input v-model="userForm.lastName" type="text" class="form-input" required />
          </div>

          <div class="form-group">
            <label>Email *</label>
            <input v-model="userForm.email" type="email" class="form-input" required />
          </div>

          <div class="form-group">
            <label>Institutional Email *</label>
            <input v-model="userForm.institutionalEmail" type="email" class="form-input" required />
          </div>

          <div class="modal-footer">
            <button type="button" @click="showCreateUser = false" class="btn-cancel">
              Cancel
            </button>
            <button type="submit" class="btn-primary" :disabled="submitting">
              {{ submitting ? 'Creating...' : 'Create User' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Edit User Modal -->
    <div v-if="editModal" class="modal-overlay" @click.self="closeEditModal">
      <div class="modal-card">
        <h3>Edit User</h3>
        <form @submit.prevent="updateUser">
          <div class="form-group">
            <label>First Name *</label>
            <input v-model="editForm.firstName" type="text" class="form-input" required />
          </div>

          <div class="form-group">
            <label>Last Name *</label>
            <input v-model="editForm.lastName" type="text" class="form-input" required />
          </div>

          <div class="form-group">
            <label>Email *</label>
            <input v-model="editForm.email" type="email" class="form-input" required />
          </div>

          <div class="modal-footer">
            <button type="button" @click="closeEditModal" class="btn-cancel">
              Cancel
            </button>
            <button type="submit" class="btn-primary" :disabled="submitting">
              {{ submitting ? 'Updating...' : 'Update User' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Change Role Modal -->
    <div v-if="roleModal" class="modal-overlay" @click.self="closeRoleModal">
      <div class="modal-card">
        <h3>Change User Role</h3>
        <div class="user-info">
          <p><strong>User:</strong> {{ roleModal.fullName }}</p>
          <p><strong>Current Role:</strong> {{ roleModal.role }}</p>
        </div>
        <form @submit.prevent="changeRole">
          <div class="form-group">
            <label>New Role *</label>
            <select v-model="roleForm.newRole" class="form-select" required>
              <option value="">Select role</option>
              <option value="admin">Admin</option>
              <option value="adviser">Adviser</option>
              <option value="faculty">Faculty</option>
              <option value="student">Student</option>
            </select>
          </div>

          <div class="modal-footer">
            <button type="button" @click="closeRoleModal" class="btn-cancel">
              Cancel
            </button>
            <button type="submit" class="btn-primary" :disabled="submitting">
              {{ submitting ? 'Changing...' : 'Change Role' }}
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { adminApi } from '@/services';

// State
const loading = ref(false);
const users = ref([]);
const roleFilter = ref('all');
const searchQuery = ref('');
const showCreateUser = ref(false);
const editModal = ref(null);
const roleModal = ref(null);
const submitting = ref(false);

// Forms
const userForm = ref({
  type: '',
  rank: '',
  firstName: '',
  lastName: '',
  email: '',
  institutionalEmail: ''
});

const editForm = ref({
  firstName: '',
  lastName: '',
  email: ''
});

const roleForm = ref({
  newRole: ''
});

// Computed
const filteredUsers = computed(() => {
  let filtered = users.value;

  // Filter by role
  if (roleFilter.value !== 'all') {
    filtered = filtered.filter(u => u.role.toLowerCase() === roleFilter.value);
  }

  // Search by name or email
  if (searchQuery.value.trim()) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(u => 
      u.fullName.toLowerCase().includes(query) || 
      u.email.toLowerCase().includes(query)
    );
  }

  return filtered;
});

// Load Users
const loadUsers = async () => {
  try {
    loading.value = true;
    const response = await adminApi.listUsers();
    console.log('Admin Users API Response:', response);
    
    // The API returns: { data: { users: [...], pagination: {...} } }
    if (response.data && response.data.data && response.data.data.users) {
      const apiUsers = response.data.data.users;
      
      // Transform API response to match component expectations
      users.value = apiUsers.map(user => ({
        id: user.UserID,
        userNumber: user.SchoolID,
        fullName: user.FullName,
        firstName: user.FullName?.split(' ')[0] || '',
        lastName: user.FullName?.split(' ').slice(1).join(' ') || '',
        email: user.Email,
        role: user.Roles && user.Roles.length > 0 ? user.Roles[0] : 'Student',
        roles: user.Roles || [],
        status: user.Status || 'Active',
        facultyType: user.FacultyType
      }));
      
      console.log('Transformed users:', users.value);
    } else {
      console.warn('Unexpected response structure:', response.data);
      users.value = [];
    }
    
    console.log('Loaded users:', users.value.length);
  } catch (err) {
    console.error('Failed to load users:', err);
    console.error('Error response:', err.response);
    
    // Show more helpful error message
    let errorMsg = 'Failed to load users.';
    if (err.response?.status === 403) {
      errorMsg = 'Access denied. Administrator privileges required.';
    } else if (err.response?.data?.message) {
      errorMsg = err.response.data.message;
    } else if (err.message) {
      errorMsg = err.message;
    }
    
    alert(errorMsg);
    users.value = [];
  } finally {
    loading.value = false;
  }
};

// Create User
const createUser = async () => {
  try {
    submitting.value = true;
    
    await adminApi.createFacultyUser({
      type: userForm.value.type,
      rank: userForm.value.rank || undefined,
      firstName: userForm.value.firstName,
      lastName: userForm.value.lastName,
      email: userForm.value.email,
      institutionalEmail: userForm.value.institutionalEmail
    });

    // Reset form and reload users
    userForm.value = {
      type: '',
      rank: '',
      firstName: '',
      lastName: '',
      email: '',
      institutionalEmail: ''
    };
    showCreateUser.value = false;
    await loadUsers();
    
    alert('User created successfully!');
  } catch (err) {
    console.error('Failed to create user:', err);
    alert(err.response?.data?.message || 'Failed to create user. Please try again.');
  } finally {
    submitting.value = false;
  }
};

// Open Edit Modal
const openEditUser = (user) => {
  editModal.value = user;
  editForm.value = {
    firstName: user.firstName || '',
    lastName: user.lastName || '',
    email: user.email
  };
};

// Close Edit Modal
const closeEditModal = () => {
  editModal.value = null;
  editForm.value = {
    firstName: '',
    lastName: '',
    email: ''
  };
};

// Update User
const updateUser = async () => {
  try {
    submitting.value = true;
    
    await adminApi.updateUser(editModal.value.id, editForm.value);

    await loadUsers();
    closeEditModal();
    
    alert('User updated successfully!');
  } catch (err) {
    console.error('Failed to update user:', err);
    alert(err.response?.data?.message || 'Failed to update user. Please try again.');
  } finally {
    submitting.value = false;
  }
};

// Open Change Role Modal
const openChangeRole = (user) => {
  roleModal.value = user;
  roleForm.value = { newRole: '' };
};

// Close Role Modal
const closeRoleModal = () => {
  roleModal.value = null;
  roleForm.value = { newRole: '' };
};

// Change Role
const changeRole = async () => {
  try {
    submitting.value = true;
    
    await adminApi.changeUserRole(roleModal.value.id, {
      newRole: roleForm.value.newRole
    });

    await loadUsers();
    closeRoleModal();
    
    alert('Role changed successfully!');
  } catch (err) {
    console.error('Failed to change role:', err);
    alert(err.response?.data?.message || 'Failed to change role. Please try again.');
  } finally {
    submitting.value = false;
  }
};

// Initialize
onMounted(() => {
  loadUsers();
});
</script>

<style scoped>
.page-view {
  padding: 20px;
  max-width: 1400px;
  margin: 0 auto;
  background: #f8fafc;
  min-height: 100vh;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 30px;
}

.header-left {
  flex: 1;
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

.btn-primary {
  background: #E8B931;
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
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

.search-input {
  flex: 1;
  padding: 10px 15px;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  font-size: 14px;
  outline: none;
}

.search-input:focus {
  border-color: #E8B931;
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

.users-table {
  width: 100%;
  border-collapse: collapse;
}

.users-table thead {
  background: #f8fafc;
}

.users-table th {
  text-align: left;
  padding: 15px;
  font-weight: 700;
  color: #1e293b;
  border-bottom: 2px solid #e2e8f0;
}

.users-table td {
  padding: 15px;
  border-bottom: 1px solid #f1f5f9;
  color: #475569;
}

.name-cell {
  font-weight: 600;
  color: #1e293b;
}

.actions-cell {
  display: flex;
  gap: 8px;
}

.btn-edit {
  background: #e2e8f0;
  color: #475569;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
}

.btn-role {
  background: #E8B931;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
}

.badge-role {
  padding: 4px 10px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
}

.badge-role.admin {
  background: #fee2e2;
  color: #991b1b;
}

.badge-role.adviser {
  background: #dbeafe;
  color: #1e40af;
}

.badge-role.faculty {
  background: #e0e7ff;
  color: #3730a3;
}

.badge-role.student {
  background: #dcfce7;
  color: #166534;
}

.badge-status {
  padding: 4px 10px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
}

.badge-status.active {
  background: #dcfce7;
  color: #166534;
}

.badge-status.inactive {
  background: #f1f5f9;
  color: #64748b;
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

.user-info {
  margin: 20px 0;
  padding: 15px;
  background: #f8fafc;
  border-radius: 8px;
}

.user-info p {
  margin: 5px 0;
  color: #475569;
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

.form-input, .form-select {
  width: 100%;
  padding: 10px;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  font-size: 14px;
  outline: none;
}

.form-input:focus, .form-select:focus {
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

.btn-primary:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
