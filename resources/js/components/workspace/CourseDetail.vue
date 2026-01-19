<template>
  <div class="course-detail-page">
    <BaseCard :title="courseTitle">
      <div class="course-header">
        <h2>{{ courseTitle }}</h2>
        <p class="course-path">{{ role }} > {{ courseName }}</p>
      </div>

      <div class="course-content">
        <!-- Tabs for course sections -->
        <div class="course-tabs">
          <div 
            v-for="tab in courseTabs" 
            :key="tab"
            :class="['tab', { active: activeTab === tab }]"
            @click="activeTab = tab"
          >
            {{ formatTabName(tab) }}
          </div>
        </div>

        <!-- Tab content -->
        <div class="tab-content">
          <div v-if="courseTabs.length === 0" class="empty-section">
            <p>No sections available for this course yet.</p>
          </div>
          <div v-else>
            <h3>{{ formatTabName(activeTab) }}</h3>
            <p>{{ getTabDescription(activeTab) }}</p>
          </div>
        </div>
      </div>

      <!-- Back button -->
      <div class="action-buttons">
        <button class="back-btn" @click="goBack">← Back to Courses</button>
      </div>
    </BaseCard>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import BaseCard from '@/components/ui/core/BaseCard.vue';

const route = useRoute();
const router = useRouter();
const activeTab = ref(null);

const role = computed(() => route.params.role);
const courseName = computed(() => route.params.course);

// Map course codes to titles
const courseMap = {
  mor: 'Methods of Research',
  dp1: 'Project Design 1',
  dp2: 'Project Design 2'
};

const courseTitle = computed(() => courseMap[courseName.value] || 'Course');

// Define tabs for each course and role
const courseTabsMap = {
  student: {
    mor: ['submission', 'feedback'],
    dp1: [],
    dp2: ['documents', 'evaluation']
  },
  admin: {
    mor: ['groups', 'enrollees', 'submission', 'evaluation', 'panel-status', 'faculty'],
    dp1: [],
    dp2: ['documents', 'evaluation']
  },
  adviser: {
    mor: ['submission', 'feedback', 'panel-status'],
    dp1: [],
    dp2: ['documents', 'evaluation']
  },
  faculty: {
    mor: ['submission', 'feedback', 'panel-status', 'faculty'],
    dp1: [],
    dp2: []
  }
};

const courseTabs = computed(() => {
  const tabs = courseTabsMap[role.value]?.[courseName.value] || [];
  if (tabs.length > 0 && !activeTab.value) {
    activeTab.value = tabs[0];
  }
  return tabs;
});

const formatTabName = (tab) => {
  return tab
    .split('-')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ');
};

const getTabDescription = (tab) => {
  const descriptions = {
    submission: 'View and manage your submissions for this course section.',
    feedback: 'Review feedback from your adviser or instructor.',
    groups: 'Manage student groups and assignments.',
    enrollees: 'View and manage course enrollees.',
    evaluation: 'View evaluation results and feedback.',
    'panel-status': 'Track the status of panel reviews.',
    faculty: 'Faculty information and contact details.',
    documents: 'Access course documents and materials.'
  };
  return descriptions[tab] || 'Course content for this section.';
};

const goBack = () => {
  router.back();
};
</script>

<style scoped>
.course-detail-page {
  padding: 50px;
  background-color: #f4f6f9;
  min-height: 100vh;
  width: 100%;
}

.course-header {
  margin-bottom: 30px;
  border-bottom: 2px solid #800000;
  padding-bottom: 20px;
}

.course-header h2 {
  color: #800000;
  font-size: 2rem;
  margin: 0 0 10px 0;
  text-transform: uppercase;
}

.course-path {
  color: #666;
  font-size: 0.9rem;
  margin: 0;
  text-transform: capitalize;
}

.course-content {
  margin: 30px 0;
}

.course-tabs {
  display: flex;
  gap: 10px;
  border-bottom: 1px solid #ddd;
  margin-bottom: 20px;
  flex-wrap: wrap;
}

.tab {
  padding: 12px 20px;
  background-color: #f0f0f0;
  border: none;
  cursor: pointer;
  border-radius: 4px 4px 0 0;
  font-weight: 500;
  transition: all 0.2s;
  color: #666;
}

.tab:hover {
  background-color: #e0e0e0;
}

.tab.active {
  background-color: #800000;
  color: white;
  border-bottom: 3px solid #800000;
}

.tab-content {
  background-color: white;
  padding: 30px;
  border-radius: 0 4px 4px 4px;
  min-height: 200px;
}

.tab-content h3 {
  color: #800000;
  margin-top: 0;
  text-transform: capitalize;
}

.empty-section {
  text-align: center;
  padding: 40px;
  color: #999;
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
</style>
