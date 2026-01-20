/**
 * Course API Service
 * All course-related API calls
 */
import api from './api';

export const courseApi = {
    // Get All Courses with Optional Filters
    getCourses(params = {}) {
        return api.get('/courses', { params });
    },

    // Get Sections (Groups) in a Course
    getCourseSections(courseId) {
        return api.get(`/courses/${courseId}/sections`);
    },

    // Get Courses where Faculty is Adviser
    getMyCoursesAsFaculty() {
        return api.get('/faculty/my-courses');
    },
};
