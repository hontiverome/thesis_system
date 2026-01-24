<template>
  <div class="sidebar-inner-content">
    <div v-for="course in courses" :key="course.id" class="course-section">
      <div class="course-header" @click="toggleSection(course.id)">
        <span class="chevron" :class="{ rotated: openSections.includes(course.id) }">
          <IconifyIcon icon="mdi:chevron-right" width="20" height="20" />
        </span>
        <h2 class="course-title" :class="{ 'title-active': activeCourseId === course.id }">
          {{ course.title }}
        </h2>
      </div>

      <transition name="slide-fade">
        <nav v-show="openSections.includes(course.id)" class="course-nav">
          <template v-for="item in course.items" :key="item.id">
            <div class="nav-item-container">
              <div v-if="item.isSubItem" class="guide-line"></div>
              <button
                class="nav-btn"
                :class="[
                  isItemActive(item) && activeCourseId === course.id ? 'btn-active' : 'btn-inactive',
                  item.isSubItem ? 'sub-item' : 'parent-item'
                ]"
                @click.stop="setActiveItem(course.id, item)"
              >
                {{ item.text }}
              </button>
            </div>
          </template>
        </nav>
      </transition>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Icon as IconifyIcon } from '@iconify/vue';
import { useSidebarLogic } from '@/composables/useSidebarLogic';

const courses = ref([
  {
    id: 'MOR',
    title: 'MOR',
    items: [
      { id: 'mor-prop', text: 'Title Proposals', courseCode: 'mor' },
      { id: 'mor-prop-sec', text: 'Sections', courseCode: 'mor', isSubItem: true, parentId: 'mor-prop' },
      { id: 'mor-chap', text: 'Chapters 1-3', courseCode: 'mor' },
      { id: 'mor-chap-sec', text: 'Sections', courseCode: 'mor', isSubItem: true, parentId: 'mor-chap' },
      { id: 'mor-status', text: 'Panel Status', courseCode: 'mor' },
      { id: 'mor-faculty', text: 'Faculty', courseCode: 'mor' }
    ]
  },
  {
    id: 'DP1',
    title: 'DP1',
    items: [
      { id: 'dp1-revised', text: 'Revised Chapters 1-3', courseCode: 'dp1' },
      { id: 'dp1-revised-sec', text: 'Sections', courseCode: 'dp1', isSubItem: true, parentId: 'dp1-revised' }
    ]
  },
  {
    id: 'DP2',
    title: 'DP2',
    items: [
      { id: 'dp2-thesis', text: 'Research/Thesis', courseCode: 'dp2' },
      { id: 'dp2-thesis-sec', text: 'Sections', courseCode: 'dp2', isSubItem: true, parentId: 'dp2-thesis' },
      { id: 'dp2-faculty', text: 'Faculty', courseCode: 'dp2' }
    ]
  }
]);

const { openSections, activeCourseId, isItemActive, toggleSection, setActiveItem } = useSidebarLogic(courses, 'admin');
</script>