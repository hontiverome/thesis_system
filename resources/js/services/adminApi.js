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

    // List Users
    listUsers(params = {}) {
        return api.get('/admin/users/list', { params });
    },

    // Change User Role
    changeUserRole(userId, roleData) {
        return api.put(`/admin/users/${userId}/role`, roleData);
    },

    // Get Available Roles
    getAvailableRoles() {
        return api.get('/admin/roles/available');
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
