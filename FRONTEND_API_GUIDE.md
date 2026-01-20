# Frontend API Integration Documentation

## Overview
This document describes the API service layer and frontend views that connect to the Laravel backend API.

## API Service Layer

### Structure
All API services are located in `resources/js/services/` and follow a consistent pattern:

```
resources/js/services/
├── api.js              # Base axios instance with auth interceptors
├── studentApi.js       # Student-specific endpoints
├── adviserApi.js       # Adviser-specific endpoints
├── adminApi.js         # Admin-specific endpoints
├── facultyApi.js       # Faculty-specific endpoints
├── courseApi.js        # Course management endpoints
└── index.js            # Central export for all services
```

### Base API Configuration (`api.js`)

```javascript
import axios from 'axios';

const api = axios.create({
  baseURL: '/api/v1',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  }
});

// Request interceptor: Add authentication token
api.interceptors.request.use(config => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Response interceptor: Handle 401 errors
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401) {
      localStorage.removeItem('auth_token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default api;
```

### Service Usage Example

```javascript
import { studentApi } from '@/services';

// Get student group info
const { data } = await studentApi.getGroupInfo();

// Submit a proposal
await studentApi.submitProposal(groupId, formData);
```

## API Endpoints by Role

### Student API (`studentApi.js`)
- `getDashboard()` - Get student dashboard data
- `getGroupInfo()` - Get current group information
- `getAllFaculty()` - Get list of all faculty members
- `submitProposal(groupId, data)` - Submit new proposal
- `getProposals(groupId)` - Get all proposals for a group
- `getApprovalStatus(proposalId)` - Check proposal approval status
- `submitManuscript(groupId, data)` - Submit manuscript draft
- `getDefenseSchedule(groupId)` - Get defense date/time/venue
- `getDefenseVerdict(groupId)` - Get defense result
- `getPanelInvitations()` - Get panel member invitations

### Adviser API (`adviserApi.js`)
- `createGroup(data)` - Create a new thesis group
- `getMyGroups()` - Get all groups advised by current user
- `addMember(groupId, data)` - Add student to a group
- `removeMember(groupId, studentId)` - Remove student from group
- `setGroupLeader(groupId, studentId)` - Assign group leader
- `getAvailableStudents()` - Get students not in any group
- `approveProposal(proposalId, data)` - Approve a proposal
- `rejectProposal(proposalId, data)` - Reject a proposal
- `getPanelInvitations()` - Get panel invitations
- `respondToInvitation(invitationId, data)` - Accept/reject panel invitation
- `uploadDocument(data)` - Upload evaluation document

### Admin API (`adminApi.js`)
- `createFacultyUser(data)` - Create faculty/student account
- `listUsers(filters)` - Get all users with filters
- `changeUserRole(userId, data)` - Change user role
- `getGroupsWithCourses()` - Get all groups and their courses
- `assignCourse(groupId, data)` - Assign course to a group

### Faculty API (`facultyApi.js`)
- `getProposals()` - Get assigned proposals for review
- `updateProposalVerdict(proposalId, data)` - Approve/reject proposal
- `respondToInvitation(invitationId, data)` - Respond to panel invitation
- `uploadEvaluationDocuments(data)` - Upload evaluation forms

### Course API (`courseApi.js`)
- `getCourses()` - Get all available courses
- `getCourseSections(courseId)` - Get sections of a course
- `getMyCoursesAsFaculty()` - Get courses assigned to faculty

## Frontend Views

### Student Dashboard (`resources/js/views/student/StudentDashboard.vue`)

**Route:** `/student/dashboard`

**Features:**
- Display group information (code, adviser, members)
- List all submitted proposals with status
- Show defense schedule
- Panel member invitations list
- Submit new proposal (group leader only)

**Key Components:**
```vue
<template>
  <div class="dashboard-grid">
    <!-- Group Info Card -->
    <div class="card">
      <h3>My Group</h3>
      <!-- Group details -->
    </div>

    <!-- Proposals Card -->
    <div class="card">
      <h3>Thesis Proposals</h3>
      <button @click="showSubmitProposal = true">+ Submit New</button>
    </div>

    <!-- Defense Card -->
    <!-- Panel Card -->
  </div>
</template>

<script setup>
import { studentApi } from '@/services';

const loadDashboard = async () => {
  const [groupResponse, proposalsResponse] = await Promise.all([
    studentApi.getGroupInfo(),
    studentApi.getProposals(groupId)
  ]);
  // Update state
};
</script>
```

### Faculty Proposals View (`resources/js/views/faculty/FacultyProposals.vue`)

**Route:** `/faculty/proposals`

**Features:**
- View all assigned proposals
- Filter by status (pending, approved, rejected)
- Review and evaluate proposals
- Submit approval/rejection with comments

**Key Functions:**
```javascript
const loadProposals = async () => {
  const response = await facultyApi.getProposals();
  proposals.value = response.data;
};

const submitReview = async () => {
  await facultyApi.updateProposalVerdict(proposalId, {
    verdict: 'approved',
    comments: 'Well-structured research'
  });
};
```

### Admin User Management (`resources/js/views/admin/UserManagement.vue`)

**Route:** `/admin/users`

**Features:**
- List all users with filtering
- Search by name or email
- Create new faculty/student accounts
- Edit user information
- Change user roles

**Key Functions:**
```javascript
const createUser = async () => {
  await adminApi.createFacultyUser({
    type: 'faculty',
    rank: 'Professor',
    firstName: 'John',
    lastName: 'Doe',
    email: 'john@example.com',
    institutionalEmail: 'john@pup.edu.ph'
  });
};

const changeRole = async (userId, newRole) => {
  await adminApi.changeUserRole(userId, { newRole });
};
```

## Authentication

### useAuth Composable (`resources/js/composables/useAuth.js`)

Already implemented with the following features:

```javascript
import { useAuth } from '@/composables/useAuth';

const {
  user,              // Current user object
  isAuthenticated,   // Boolean
  isAdmin,           // Boolean
  isStudent,         // Boolean
  isFaculty,         // Boolean
  isAdviser,         // Boolean
  isGroupLeader,     // Boolean
  can,               // Check permission
  isAuthorized,      // Check role
  login,             // Login function
  logout             // Logout function
} = useAuth();
```

## Design Patterns

### 1. Consistent Component Structure
```vue
<template>
  <!-- Loading state -->
  <div v-if="loading">...</div>
  
  <!-- Error state -->
  <div v-if="error">...</div>
  
  <!-- Main content -->
  <div v-if="!loading && !error">...</div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { serviceApi } from '@/services';

const loading = ref(false);
const error = ref(null);
const data = ref([]);

const loadData = async () => {
  try {
    loading.value = true;
    error.value = null;
    const response = await serviceApi.getData();
    data.value = response.data;
  } catch (err) {
    error.value = 'Failed to load data';
    console.error(err);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  loadData();
});
</script>
```

### 2. Form Handling with Modals
```vue
<div v-if="showModal" class="modal-overlay" @click.self="showModal = false">
  <div class="modal-card">
    <h3>Modal Title</h3>
    <form @submit.prevent="submitForm">
      <div class="form-group">
        <label>Field *</label>
        <input v-model="form.field" required />
      </div>
      <div class="modal-footer">
        <button type="button" @click="showModal = false">Cancel</button>
        <button type="submit" :disabled="submitting">Submit</button>
      </div>
    </form>
  </div>
</div>
```

### 3. Status Badges
```vue
<span :class="['badge-status', item.status.toLowerCase()]">
  {{ item.status }}
</span>

<style>
.badge-status.pending { background: #fff7ed; color: #9a3412; }
.badge-status.approved { background: #dcfce7; color: #166534; }
.badge-status.rejected { background: #fee2e2; color: #991b1b; }
</style>
```

## Error Handling

### API Error Handling Pattern
```javascript
try {
  await api.someEndpoint(data);
  alert('Success!');
} catch (err) {
  // Display backend error message or fallback
  alert(err.response?.data?.message || 'Operation failed');
  console.error('Error details:', err);
}
```

### Form Validation
```vue
<form @submit.prevent="submit">
  <input v-model="form.field" required />
  <button type="submit" :disabled="submitting">
    {{ submitting ? 'Submitting...' : 'Submit' }}
  </button>
</form>
```

## Styling Guidelines

### Color Scheme
- Primary: `#E8B931` (PUP Yellow)
- Text Dark: `#1e293b`
- Text Light: `#64748b`
- Border: `#e2e8f0`
- Background: `#f8fafc`

### Common Classes
- `.page-view` - Main page container
- `.page-header` - Page title section
- `.card` - Content card with border and shadow
- `.btn-primary` - Primary action button
- `.modal-overlay` - Fullscreen modal backdrop
- `.loading-state` - Centered loading spinner

## Testing the Integration

### 1. Start the Development Server
```bash
npm run dev
```

### 2. Test Routes
- Student: http://localhost:8000/student/dashboard
- Faculty: http://localhost:8000/faculty/proposals
- Admin: http://localhost:8000/admin/users

### 3. Check Console
Open browser DevTools and check:
- Network tab for API calls
- Console for error messages
- Application tab for auth token

## Next Steps

### To Integrate More Components:

1. **Identify the component** you want to connect
2. **Determine which API service** it needs
3. **Import the service** at the top of the script:
   ```javascript
   import { studentApi } from '@/services';
   ```
4. **Replace mock data** with API calls:
   ```javascript
   const data = ref([]);
   
   onMounted(async () => {
     const response = await studentApi.getData();
     data.value = response.data;
   });
   ```
5. **Add loading and error states**
6. **Test the integration**

### Example: Updating an Existing Component

```javascript
// Before (with mock data)
const groups = ref([
  { id: 1, name: 'Group 1', members: [] }
]);

// After (with API)
import { adviserApi } from '@/services';

const groups = ref([]);
const loading = ref(false);

onMounted(async () => {
  try {
    loading.value = true;
    const response = await adviserApi.getMyGroups();
    groups.value = response.data;
  } catch (err) {
    console.error('Failed to load groups:', err);
  } finally {
    loading.value = false;
  }
});
```

## API Response Format

All API responses follow this structure:

```javascript
// Success Response
{
  data: [...],  // or { ... }
  message: "Operation successful",
  status: 200
}

// Error Response
{
  message: "Error description",
  errors: {
    field: ["Validation error message"]
  },
  status: 422
}
```

## File Upload Example

```javascript
const handleFileUpload = async (event) => {
  const file = event.target.files[0];
  
  const formData = new FormData();
  formData.append('file', file);
  formData.append('title', 'Document Title');
  
  try {
    await adviserApi.uploadDocument(formData);
    alert('File uploaded successfully!');
  } catch (err) {
    alert('Upload failed: ' + err.response?.data?.message);
  }
};
```

## Common Issues & Solutions

### Issue: 401 Unauthorized
**Solution:** Check if auth token exists in localStorage
```javascript
const token = localStorage.getItem('auth_token');
console.log('Token:', token);
```

### Issue: CORS Error
**Solution:** Ensure Laravel backend has proper CORS configuration in `config/cors.php`

### Issue: API Call Returns Empty Data
**Solution:** Check:
1. Backend route exists (`php artisan route:list`)
2. Database has seeded data
3. User has proper permissions
4. Console for error messages

### Issue: Component Not Updating
**Solution:** Ensure data is reactive:
```javascript
// Wrong
let data = [];

// Correct
const data = ref([]);
```

## Additional Resources

- [Vue 3 Composition API](https://vuejs.org/guide/extras/composition-api-faq.html)
- [Axios Documentation](https://axios-http.com/docs/intro)
- [Laravel API Resources](https://laravel.com/docs/eloquent-resources)
- [Inertia.js Guide](https://inertiajs.com/)
