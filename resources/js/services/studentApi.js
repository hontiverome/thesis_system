/**
 * Student API Service
 * All student-related API calls
 */
import api from './api';

export const studentApi = {
    // F-103: Dashboard
    getDashboard() {
        return api.get('/auth/dashboard');
    },

    // Display Group Information
    getGroupInfo() {
        return api.get('/groups/group');
    },

    // F-104: Submit Proposal (Group Leader)
    submitProposal(groupId, proposalData) {
        return api.post(`/groups/${groupId}/proposals`, proposalData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
    },

    // F-105: Get Proposals
    getProposals(groupId) {
        return api.get(`/groups/${groupId}/proposals`);
    },

    // F-107: Get Approval Status (Group Leader)
    getApprovalStatus(groupId) {
        return api.get(`/groups/${groupId}/approval-status`);
    },

    // F-108: Select Title for Defense (Group Leader)
    selectTitleForDefense(groupId, data) {
        return api.post(`/groups/${groupId}/select-title`, data);
    },

    // F-109: Submit Manuscript (Group Leader)
    submitManuscript(groupId, manuscriptData) {
        return api.post(`/groups/${groupId}/manuscript`, manuscriptData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
    },

    // Get Manuscript
    getManuscript(groupId) {
        return api.get(`/groups/${groupId}/manuscript`);
    },

    // F-113: Get Defense Verdict
    getDefenseVerdict(groupId) {
        return api.get(`/groups/${groupId}/defense`);
    },

    // Display Faculty
    getAllFaculty() {
        return api.get('/groups/faculty');
    },

    // Display Panel Invitations
    getPanelInvitations() {
        return api.get('/groups/invitation');
    },

    // Get Evaluation Forms
    getEvaluationForms() {
        return api.get('/groups/evaluation-forms');
    },

    // Delete Proposal (Group Leader)
    deleteProposal(proposalId) {
        return api.get(`/groups/${proposalId}/delete`);
    },
};
