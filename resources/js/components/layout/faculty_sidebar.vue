<script setup>
import { ref } from 'vue';
import { Icon as IconifyIcon } from '@iconify/vue';
import { useSidebarLogic } from '@/composables/useSidebarLogic';

const courses = ref([
  {
    id: 'MOR', title: 'MOR',
    items: [
      { id: 'fa-mor-prop', text: 'Title Proposals', courseCode: 'mor' },
      { id: 'fa-mor-prop-sec', text: 'Sections', courseCode: 'mor', isSubItem: true, parentId: 'fa-mor-prop' },
      { id: 'fa-mor-status', text: 'Panel Status', courseCode: 'mor' },
      { id: 'fa-mor-fac', text: 'Faculty', courseCode: 'mor' }
    ]
  },
  { id: 'DP1', title: 'DP1', items: [] },
  { id: 'DP2', title: 'DP2', items: [] }
]);

const { openSections, activeCourseId, isItemActive, toggleSection, setActiveItem } = useSidebarLogic(courses, 'faculty');
</script>

<template>
  <div class="sidebar-inner-content">
    </div>
</template>