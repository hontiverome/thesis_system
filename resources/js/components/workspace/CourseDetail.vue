<template>
  <div class="course-detail-page">
    <BaseCard :title="courseTitle">
      <div class="course-header">
        <h2 class="course-title-text">{{ courseTitle }}</h2>
        <p class="course-path">{{ roleLabel }} > {{ courseCode.toUpperCase() }}</p>
      </div>

      <div class="course-content">
        <div class="course-tabs">
          <router-link 
            v-for="tabKey in activeTabs" 
            :key="tabKey"
            :to="`/${role}/course/${courseCode}/${tabKey}`"
            class="tab" 
            active-class="active"
          >
            {{ tabKey.replace(/-/g, ' ').toUpperCase() }}
          </router-link>
        </div>

        <div class="tab-content">
          <router-view v-slot="{ Component }">
            <transition name="fade" mode="out-in">
              <component :is="Component" v-if="Component" />
              <div v-else class="error-msg">Content not found for this tab.</div>
            </transition>
          </router-view>
        </div>
      </div>
    </BaseCard>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useWorkspaceApi } from '@/composables/useWorkspaceApi';
import { COURSE_TABS, COURSE_MAP } from '@/config/roleConfig';
import BaseCard from '@/components/ui/core/BaseCard.vue';

// Pulls real-time context from the URL parameters via our composable
const { role, course: courseCode, roleLabel, courseTitle } = useWorkspaceApi();

// Identifies which tabs to show based on the user's role in the config
const activeTabs = computed(() => COURSE_TABS[role.value] || ['overview']);
</script>

<style scoped>
.course-detail-page {
  min-height: 100vh;
  padding: 40px;
  /* Unified 0.35 opacity background for the T-SIS project theme */
  background: linear-gradient(rgba(17, 22, 28, 0.35), rgba(17, 22, 28, 0.35)),
              url('/assets/aerial_pup.jpg') center/cover no-repeat fixed;
}

.course-title-text {
  color: #ffffff !important; /* Forces white color against dark aerial spots */
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.6);
  font-weight: bold;
  text-transform: uppercase;
}

.course-path {
  color: #ffffff;
  opacity: 0.9;
  font-size: 0.85rem;
  margin-top: 5px;
}

.course-tabs {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.tab {
  padding: 10px 20px;
  text-decoration: none;
  color: #ffffff;
  font-weight: bold;
}

.tab.active {
  background: #800000; /* University Red accent */
  color: white;
  border-radius: 4px 4px 0 0;
}

.tab-content {
  background: white;
  padding: 25px;
  min-height: 400px;
  border-radius: 8px;
}
</style>