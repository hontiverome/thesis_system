/**
 * Admin API Service
 * All admin-related API calls
 */
import api from './api';

export const adminApi = {
    // Create Faculty User
    createFacultyUser(userData) {
        return api.post('/admin/users', userData);
    },

    // List Users (alias for getUsers for compatibility)
    listUsers(params = {}) {
        return api.get('/admin/users/list', { params });
    },

    // Get Users (alias for listUsers)
    getUsers(params = {}) {
        return api.get('/admin/users/list', { params });
    },

    // Update User
    updateUser(userId, userData) {
        return api.put(`/admin/users/${userId}`, userData);
    },

    // Delete User
    deleteUser(userId) {
        return api.delete(`/admin/users/${userId}`);
    },

    // Change User Role
    changeUserRole(userId, roleData) {
        return api.put(`/admin/users/${userId}/role`, roleData);
    },

    // Get Available Roles
    getAvailableRoles() {
        return api.get('/admin/roles/available');
    },

    // Get Proposals
    getProposals(params = {}) {
        return api.get('/admin/proposals', { params });
    },

    // Update Proposal Status
    updateProposalStatus(proposalId, status) {
        return api.put(`/admin/proposals/${proposalId}/status`, { status });
    },

    // Get Defense Panels
    getDefensePanels(params = {}) {
        return api.get('/admin/defenses', { params });
    },

    // Get Submissions
    getSubmissions(params = {}) {
        return api.get('/admin/submissions', { params });
    },

    // F-017: Get Groups with Courses
    getGroupsWithCourses() {
        return api.get('/admin/groups');
    },

    // F-017: Assign Course to Group
    assignCourse(groupId, courseData) {
        return api.put(`/admin/groups/${groupId}/course`, courseData);
    },

    // F-017: Get Available Courses
    getAvailableCourses() {
        return api.get('/admin/courses/available');
    },
};
