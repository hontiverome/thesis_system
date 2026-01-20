/**
 * Faculty API Service
 * All faculty-related API calls
 */
import api from './api';

export const facultyApi = {
    // F-106: Get All Proposals from Assigned Groups
    getProposals() {
        return api.get('/faculty/proposals');
    },

    // F-106: Get Proposal Details
    getProposalDetails(proposalId) {
        return api.get(`/faculty/proposals/${proposalId}`);
    },

    // F-106: Submit or Update Approval Verdict
    updateProposalVerdict(proposalId, verdictData) {
        return api.patch(`/proposals/${proposalId}/verdict`, verdictData);
    },

    // F-111: Get All Panel Invitations
    getMyInvitations() {
        return api.get('/faculty/me/invitations');
    },

    // F-111: Get Specific Panel Invitation
    getInvitationDetails(defenseId) {
        return api.get(`/faculty/me/invitations/${defenseId}`);
    },

    // F-111: Accept or Decline Panel Invitation
    respondToInvitation(defenseId, responseData) {
        return api.post(`/invitations/${defenseId}/response`, responseData);
    },

    // F-112: Upload Evaluation Documents
    uploadEvaluationDocuments(defenseId, documents) {
        return api.post(`/defenses/${defenseId}/documents`, documents, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
    },

    // F-112: Get Evaluation Documents
    getEvaluationDocuments(defenseId) {
        return api.get(`/defenses/${defenseId}/documents`);
    },

    // Get Available Faculty
    getAvailableFaculty() {
        return api.get('/faculty/available');
    },

    // Get Panel Proposals List
    getPanelProposals() {
        return api.get('/faculty/panel/proposals');
    },

    // Get Panel Assignment Statistics
    getPanelStats() {
        return api.get('/faculty/panel/proposals/stats');
    },

    // Get My Courses (where faculty is adviser)
    getMyCourses() {
        return api.get('/faculty/my-courses');
    },
};
