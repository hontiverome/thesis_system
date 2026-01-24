<template>
  <div class="section-detail-page">
    <BaseCard :title="pageTitle">
      <div class="section-header">
        <div class="header-content">
          <h1 class="main-title">{{ courseTitle }}</h1>
          <button v-if="role === 'admin'" class="delete-btn" @click="$emit('delete-section')">
            Delete Section
          </button>
        </div>
        <div class="section-info">
          <p class="class-name">Section {{ sectionNumber }}</p>
          <p class="adviser-name">{{ adviserName }}</p>
        </div>
      </div>

      <SubNavbar v-model="activeTab" :tabs="tabsList" />

      <div v-if="activeTab === 'title-proposals'" class="tab-content">
        <div class="controls-section">
          <input v-model="searchQuery" type="text" class="search-input" placeholder="Search proposals...">
          <select v-model="statusFilter" class="filter-select">
            <option value="">All Proposals</option>
            <option value="pending">Pending</option>
            <option value="accepted">Accepted</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>

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
                <tr v-for="(proposal, index) in group.items" :key="proposal.id" class="proposal-row">
                  <td v-if="index === 0" class="group-code" :rowspan="group.items.length">{{ group.groupCode }}</td>
                  <td class="research-title">{{ proposal.title }}</td>
                  <td class="status-cell">
                    <span class="status-badge" :class="proposal.status">{{ proposal.status }}</span>
                  </td>
                  <td class="actions-cell">
                    <button class="accept-btn" @click="$emit('update-status', { id: proposal.id, status: 'accepted' })">Accept</button>
                    <button class="reject-btn" @click="$emit('update-status', { id: proposal.id, status: 'rejected' })">Reject</button>
                  </td>
                </tr>
                <tr class="group-separator"></tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>

      <div class="action-buttons">
        <button class="back-btn" @click="goBack">← Back</button>
      </div>
    </BaseCard>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import BaseCard from '@/components/ui/core/BaseCard.vue';
import SubNavbar from '@/components/ui/core/SubNavbar.vue';

const props = defineProps({
  courseTitle: { type: String, required: true },
  adviserName: { type: String, default: 'TBA' },
  proposals: { type: Array, default: () => [] },
  tabsList: { type: Array, default: () => ['title-proposals', 'panel-status', 'faculty'] }
});

const emit = defineEmits(['update-status', 'delete-section']);
const route = useRoute();
const router = useRouter();

const role = computed(() => route.params.role);
const sectionNumber = computed(() => route.params.section);
const pageTitle = computed(() => `${props.courseTitle} - Section ${sectionNumber.value}`);

const activeTab = ref('title-proposals');
const searchQuery = ref('');
const statusFilter = ref('');

const filteredProposals = computed(() => {
  return props.proposals.filter(p => {
    const matchesSearch = p.title.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesFilter = !statusFilter.value || p.status === statusFilter.value;
    return matchesSearch && matchesFilter;
  });
});

const groupedProposals = computed(() => {
  const groups = {};
  filteredProposals.value.forEach(p => {
    if (!groups[p.groupCode]) groups[p.groupCode] = [];
    groups[p.groupCode].push(p);
  });
  return Object.entries(groups).map(([groupCode, items]) => ({ groupCode, items }));
});

const goBack = () => router.back();
</script>

<style scoped>
.section-header { margin-bottom: 30px; border-bottom: 3px solid #800000; padding-bottom: 20px; }
.header-content { display: flex; justify-content: space-between; align-items: center; }
.main-title { color: #800000; font-size: 2.5rem; text-transform: uppercase; font-weight: 700; margin: 0; }
.delete-btn { border: 1px solid #dc3545; color: #dc3545; background: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; }
.delete-btn:hover { background: #dc3545; color: white; }
.controls-section { display: flex; gap: 20px; margin: 20px 0; }
.search-input, .filter-select { padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
.search-input { flex: 1; }
.table-container { background: white; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.proposals-table { width: 100%; border-collapse: collapse; }
.proposals-table th { background: #800000; color: white; padding: 15px; text-align: left; }
.status-badge { padding: 5px 12px; border-radius: 15px; font-size: 0.8rem; font-weight: bold; text-transform: uppercase; }
.status-badge.accepted { background: #d4edda; color: #155724; }
.status-badge.rejected { background: #f8d7da; color: #721c24; }
.status-badge.pending { background: #fff3cd; color: #856404; }
.actions-cell { display: flex; gap: 5px; }
.accept-btn { background: #28a745; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; }
.reject-btn { background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; }
</style>