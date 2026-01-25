// ProposalsTemplate is a shared template used for displaying proposal-related information 
// and actions in various views for faculty, admin, advisers, and students.

<template>
  <div class="coming-soon-template">
    <BaseCard :title="`${courseTitleComputed}`">
      <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="icon-wrapper mb-8">
          <IconifyIcon 
            icon="mdi:tools" 
            class="text-6xl text-maroon-200 animate-bounce" 
          />
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-4">
          {{ tabTitleComputed }} is Coming Soon
        </h2>
        
        <p class="text-gray-600 max-w-md mb-10">
          We are currently building the <strong>{{ roleLabelComputed }}</strong> 
          interface for this module. You will be able to manage your 
          {{ tabTitleComputed.toLowerCase() }} here shortly.
        </p>

        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 flex items-center shadow-sm">
          <span class="text-2xl mr-4">💡</span>
          <p class="text-sm text-gray-500 text-left">
            Need to go back? Use the <strong>Sidebar Menu</strong> <br />
            or return to your <router-link :to="`/${route.params.role}/home`" class="text-maroon-700 font-bold hover:underline">Dashboard Home</router-link>.
          </p>
        </div>
      </div>
    </BaseCard>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import { Icon as IconifyIcon } from '@iconify/vue';
import BaseCard from '@/components/ui/core/BaseCard.vue';

const route = useRoute();

const courseMap = {
  mor: 'Methods of Research',
  dp1: 'Project Design 1',
  dp2: 'Project Design 2'
};

const roleMap = {
  admin: 'Administrator',
  faculty: 'Faculty',
  adviser: 'Adviser',
  student: 'Student'
};

const courseTitleComputed = computed(() => {
  const courseKey = route.params.course?.toLowerCase();
  return courseMap[courseKey] || 'Course';
});

const roleLabelComputed = computed(() => {
  return roleMap[route.params.role] || 'User';
});

const tabTitleComputed = computed(() => {
  // Pulls the title from route meta or cleans the URL path
  return route.meta.title || 'Module';
});
</script>

<style scoped>
.coming-soon-template {
  padding: 20px;
}
.text-maroon-200 { color: #f5e6e6; }
.text-maroon-700 { color: #800000; }
.animate-bounce {
  animation: bounce 2s infinite;
}
@keyframes bounce {
  0%, 100% { transform: translateY(-10%); animation-timing-function: cubic-bezier(0.8,0,1,1); }
  50% { transform: none; animation-timing-function: cubic-bezier(0,0,0.2,1); }
}
</style>