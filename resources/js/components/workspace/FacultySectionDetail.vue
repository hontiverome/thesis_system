<template>
  <div class="faculty-section-detail-page">
    <BaseCard :title="pageTitle">
      <!-- Header Section -->
      <div class="section-header">
        <h1 class="main-title">{{ courseTitle }}</h1>
        <div class="section-info">
          <p class="class-name">Section {{ sectionNumber }}</p>
          <p class="adviser-name">Professor {{ sectionNumber }}</p>
        </div>
      </div>

      <!-- SubNavbar Tabs -->
      <SubNavbar 
        v-model="activeTab" 
        :tabs="tabsList"
      />

      <!-- Tab Content: Title Proposals -->
      <div v-if="activeTab === 'title-proposals'" class="tab-content">
        <!-- Search and Filter Section -->
        <div class="controls-section">
        <div class="search-container">
          <input 
            v-model="searchQuery"
            type="text" 
            class="search-input"
            placeholder="Search by research title..."
          >
        </div>
        <div class="filter-container">
          <select v-model="statusFilter" class="filter-select">
            <option value="">All Proposals</option>
            <option value="pending">Pending</option>
            <option value="accepted">Accepted</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>
      </div>

      <!-- Groups Table -->
      <div class="table-container">
        <table class="proposals-table">
          <thead>
            <tr>
              <th>Group Code</th>
              <th>Research Title</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <template v-for="group in groupedProposals" :key="group.groupCode">
              <tr v-for="(proposal, index) in group.items" :key="`${proposal.groupCode}-${index}`" class="proposal-row" :class="{ 'group-first': index === 0 }">
                <td v-if="index === 0" class="group-code" :rowspan="group.items.length">{{ group.groupCode }}</td>
                <td class="research-title">{{ proposal.title }}</td>
                <td class="status-cell">
                  <span class="status-badge" :class="proposal.status">
                    {{ formatStatus(proposal.status) }}
                  </span>
                </td>
                <td class="actions-cell">
                  <button class="accept-btn" @click="acceptProposalItem(proposal.groupCode, index)">Accept</button>
                  <button class="reject-btn" @click="rejectProposalItem(proposal.groupCode, index)">Reject</button>
                </td>
              </tr>
              <tr v-if="index === group.items.length - 1" class="group-separator"></tr>
            </template>
          </tbody>
        </table>
      </div>
      </div>

      <!-- Tab Content: Panel Status -->
      <div v-if="activeTab === 'panel-status'" class="tab-content">
        <div class="placeholder-content">
          <h3>Panel Status</h3>
          <p>Panel evaluation status and tracking information will be displayed here.</p>
        </div>
      </div>

      <!-- Tab Content: Faculty -->
      <div v-if="activeTab === 'faculty'" class="tab-content">
        <div class="placeholder-content">
          <h3>Faculty Information</h3>
          <p>Faculty information and contact details will be displayed here.</p>
        </div>
      </div>

      <!-- Back button -->
      <div class="action-buttons">
        <button class="back-btn" @click="goBack">← Back to Sections</button>
      </div>
    </BaseCard>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import BaseCard from '@/components/ui/core/BaseCard.vue';
import SubNavbar from '@/components/ui/core/SubNavbar.vue';

const route = useRoute();
const router = useRouter();
const searchQuery = ref('');
const statusFilter = ref('');
const activeTab = ref('title-proposals');

const tabsList = ['title-proposals', 'panel-status', 'faculty'];

const role = computed(() => route.params.role);
const courseName = computed(() => route.params.course);
const sectionParam = computed(() => route.params.section);

// Map course codes to titles
const courseMap = {
  mor: 'Methods of Research',
  dp1: 'Project Design 1',
  dp2: 'Project Design 2'
};

const courseTitle = computed(() => courseMap[courseName.value] || 'Course');

// Extract section number from section param (e.g., "3-1" -> "3-1")
const sectionNumber = computed(() => sectionParam.value);

const pageTitle = computed(() => `${courseTitle.value} - Section ${sectionNumber.value}`);

// Generate mock group data based on section number
const generateGroupsForSection = () => {
  // Extract section number for group code prefix (e.g., "3-1" -> "31")
  const [major, minor] = sectionNumber.value.split('-');
  const prefix = major + minor; // "31" for section 3-1

  const groupTitles = [
    `AI-Based Student Performance Prediction in ${courseTitle.value}`,
    `Machine Learning Models for Thesis Evaluation System`,
    `Deep Learning Approach to Academic Text Classification`,
    `Natural Language Processing for Research Proposal Analysis`
  ];

  const proposals = [];
  const statuses = ['pending', 'pending', 'accepted', 'rejected'];

  statuses.forEach((groupStatus, groupIndex) => {
    const groupCode = `${prefix}${String(groupIndex + 1).padStart(2, '0')}`;
    groupTitles.forEach((title, titleIndex) => {
      proposals.push({
        id: `${groupCode}-${titleIndex}`,
        groupId: groupCode,
        groupCode: groupCode,
        title: title,
        status: groupStatus
      });
    });
  });

  return proposals;
};

const proposals = ref(generateGroupsForSection());

const filteredProposals = computed(() => {
  return proposals.value.filter(proposal => {
    const matchesSearch = searchQuery.value === '' || 
      proposal.title.toLowerCase().includes(searchQuery.value.toLowerCase());
    
    const matchesFilter = statusFilter.value === '' ||
      proposal.status === statusFilter.value;
    
    return matchesSearch && matchesFilter;
  });
});

const groupedProposals = computed(() => {
  const groups = {};
  filteredProposals.value.forEach(proposal => {
    if (!groups[proposal.groupCode]) {
      groups[proposal.groupCode] = [];
    }
    groups[proposal.groupCode].push(proposal);
  });
  return Object.entries(groups).map(([groupCode, items]) => ({
    groupCode,
    items
  }));
});

const formatStatus = (status) => {
  const statusMap = {
    pending: 'Pending',
    accepted: 'Accepted',
    rejected: 'Rejected'
  };
  return statusMap[status] || status;
};

const acceptProposalItem = (groupCode, titleIndex) => {
  const proposal = proposals.value.find(p => p.groupCode === groupCode && p.title === proposals.value.find(pp => pp.groupCode === groupCode).title);
  if (proposal) {
    proposal.status = 'accepted';
  }
};

const rejectProposalItem = (groupCode, titleIndex) => {
  const proposal = proposals.value.find(p => p.groupCode === groupCode && p.title === proposals.value.find(pp => pp.groupCode === groupCode).title);
  if (proposal) {
    proposal.status = 'rejected';
  }
};

const goBack = () => {
  router.back();
};
</script>

<style scoped>
.faculty-section-detail-page {
  padding: 50px;
  background-color: #f4f6f9;
  min-height: 100vh;
  width: 100%;
}

.section-header {
  margin-bottom: 30px;
  border-bottom: 3px solid #800000;
  padding-bottom: 20px;
}

.main-title {
  color: #800000;
  font-size: 2.5rem;
  margin: 0 0 15px 0;
  text-transform: uppercase;
  font-weight: 700;
}

.section-info {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.class-name {
  color: #333;
  font-size: 1.1rem;
  margin: 0;
  font-weight: 600;
}

.adviser-name {
  color: #666;
  font-size: 1rem;
  margin: 0;
}

.tab-content {
  animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.placeholder-content {
  background-color: white;
  padding: 40px;
  border-radius: 4px;
  text-align: center;
  color: #999;
}

.placeholder-content h3 {
  color: #800000;
  margin-top: 0;
  margin-bottom: 15px;
}

.placeholder-content p {
  margin: 0;
  font-size: 0.95rem;
}

.controls-section {
  display: flex;
  gap: 20px;
  margin-bottom: 30px;
  align-items: center;
  flex-wrap: wrap;
}

.search-container {
  flex: 1;
  min-width: 250px;
}

.search-input {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
  transition: border-color 0.2s;
}

.search-input:focus {
  outline: none;
  border-color: #800000;
}

.filter-container {
  min-width: 180px;
}

.filter-select {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
  background-color: white;
  cursor: pointer;
  transition: border-color 0.2s;
}

.filter-select:focus {
  outline: none;
  border-color: #800000;
}

.table-container {
  background-color: white;
  border-radius: 4px;
  overflow: hidden;
  margin-bottom: 30px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.proposals-table {
  width: 100%;
  border-collapse: collapse;
}

.proposals-table thead {
  background-color: #800000;
  color: white;
}

.proposals-table thead th {
  padding: 16px;
  text-align: left;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.9rem;
}

.proposals-table tbody tr {
  border-bottom: 1px solid #eee;
  transition: background-color 0.2s;
}

.proposals-table tbody tr:hover {
  background-color: #f9f9f9;
}

.proposal-row td {
  padding: 16px;
  vertical-align: middle;
}

.proposal-row.group-first td {
  border-top: 2px solid #800000;
}

.group-separator {
  height: 8px;
  background-color: #f9f9f9;
}

.group-code {
  font-weight: 600;
  color: #800000;
  font-size: 1rem;
  width: 100px;
  flex-shrink: 0;
  text-align: center;
}

.research-title {
  flex: 1;
  min-width: 350px;
  line-height: 1.5;
  color: #333;
  font-size: 0.95rem;
}

.status-cell {
  text-align: center;
  width: 120px;
}

.status-badge {
  display: inline-block;
  padding: 8px 16px;
  border-radius: 20px;
  font-weight: 600;
  font-size: 0.9rem;
}

.status-badge.accepted {
  background-color: #d4edda;
  color: #155724;
}

.status-badge.rejected {
  background-color: #f8d7da;
  color: #721c24;
}

.status-badge.pending {
  background-color: #fff3cd;
  color: #856404;
}

.actions-cell {
  display: flex;
  gap: 8px;
  width: 160px;
}

.accept-btn,
.reject-btn {
  flex: 1;
  padding: 8px 12px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 600;
  font-size: 0.85rem;
  transition: opacity 0.2s;
  text-transform: uppercase;
}

.accept-btn {
  background-color: #28a745;
  color: white;
}

.accept-btn:hover {
  opacity: 0.85;
}

.reject-btn {
  background-color: #dc3545;
  color: white;
}

.reject-btn:hover {
  opacity: 0.85;
}

.action-buttons {
  margin-top: 30px;
  display: flex;
  gap: 10px;
}

.back-btn {
  background-color: #800000;
  color: white;
  border: none;
  padding: 12px 24px;
  cursor: pointer;
  font-weight: bold;
  border-radius: 4px;
  transition: opacity 0.2s;
}

.back-btn:hover {
  opacity: 0.9;
}

@media (max-width: 1024px) {
  .controls-section {
    flex-direction: column;
    align-items: stretch;
  }

  .search-container,
  .filter-container {
    min-width: unset;
  }

  .group-row td {
    padding: 12px;
  }

  .proposals-table {
    font-size: 0.9rem;
  }
}

@media (max-width: 768px) {
  .main-title {
    font-size: 1.8rem;
  }

  .faculty-section-detail-page {
    padding: 20px;
  }

  .actions-cell {
    flex-direction: column;
    width: auto;
  }

  .accept-btn,
  .reject-btn {
    width: 100%;
  }
}
</style>
