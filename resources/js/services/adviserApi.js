/**
 * Adviser API Service
 * All adviser-related API calls
 */
import api from './api';

export const adviserApi = {
    // F-012: Create New Group
    createGroup(groupData) {
        return api.post('/adviser/groups', groupData);
    },

    // Get My Groups
    getMyGroups() {
        return api.get('/adviser/groups/my');
    },

    // Get Advised Groups
    getAdvisedGroups() {
        return api.get('/adviser/groups');
    },

    // Get Group Details
    getGroupDetails(groupId) {
        return api.get(`/adviser/groups/${groupId}`);
    },

    // Delete Group
    deleteGroup(groupId) {
        return api.delete(`/adviser/groups/${groupId}`);
    },

    // F-013: Add Member to Group
    addMember(groupId, memberData) {
        return api.post(`/adviser/groups/${groupId}/members`, memberData);
    },

    // F-013: Remove Member from Group
    removeMember(groupId, studentUserId) {
        return api.delete(`/adviser/groups/${groupId}/members/${studentUserId}`);
    },

    // F-014: Set Group Leader
    setGroupLeader(groupId, leaderData) {
        return api.put(`/adviser/groups/${groupId}/leader`, leaderData);
    },

    // Get Available Students
    getAvailableStudents() {
        return api.get('/adviser/students/available');
    },

    // F-016: Get Adviser Courses
    getCourses() {
        return api.get('/adviser/courses');
    },

    // F-018: Get Proposals
    getProposals() {
        return api.get('/adviser/proposals');
    },

    // F-019: Get Panel Invitations
    getPanelInvitations() {
        return api.get('/adviser/panel/invitations');
    },

    // F-019: Respond to Panel Invitation
    respondToInvitation(defenseId, response) {
        return api.post(`/adviser/panel/invitations/${defenseId}/respond`, response);
    },

    // Get Defenses to Evaluate (Panel Member)
    getDefensesToEvaluate() {
        return api.get('/adviser/panel/defenses');
    },

    // Submit Verdict (Panel Member)
    submitVerdict(defenseId, verdictData) {
        return api.post(`/adviser/panel/defenses/${defenseId}/verdict`, verdictData);
    },

    // F-020: Get Group Defenses
    getDefenses() {
        return api.get('/adviser/defenses');
    },

    // F-020: Update Defense Status
    updateDefenseStatus(defenseId, statusData) {
        return api.put(`/adviser/defenses/${defenseId}/status`, statusData);
    },

    // F-021: Upload Defense Document
    uploadDocument(defenseId, documentData) {
        return api.post(`/adviser/defenses/${defenseId}/documents`, documentData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
    },

    // F-021: Get Defense Documents
    getDefenseDocuments(defenseId) {
        return api.get(`/adviser/defenses/${defenseId}/documents`);
    },

    // F-021: Delete Document
    deleteDocument(fileId) {
        return api.delete(`/adviser/documents/${fileId}`);
    },

    // Assign Adviser to Block
    assignAdviserToBlock(blockData) {
        return api.post('/blocks/assign-adviser', blockData);
    },

    // Get Blocks for Dropdown
    getBlocksForDropdown() {
        return api.get('/blocks/available');
    },
};
