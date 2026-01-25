import { ref, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';

export function useSidebarLogic(courses, defaultRole = 'student') {
  const route = useRoute();
  const router = useRouter();

  const openSections = ref([]);
  const activeCourseId = ref('MOR');
  const activeItemId = ref('');

  const isItemActive = (item) => {
    if (activeItemId.value === item.id) return true;
    const activeItemData = courses.value.flatMap(c => c.items).find(i => i.id === activeItemId.value);
    return activeItemData?.parentId === item.id;
  };

  const syncState = () => {
    const coursePath = route.params.course;
    const tabPath = route.params[0];
    if (coursePath) {
      const courseId = coursePath.toUpperCase();
      activeCourseId.value = courseId;
      if (!openSections.value.includes(courseId)) openSections.value = [courseId];
      const course = courses.value.find(c => c.id === courseId);
      if (course && tabPath) {
        const match = course.items.find(item => item.text.toLowerCase().replace(/\s+/g, '-') === tabPath);
        if (match) activeItemId.value = match.id;
      }
    }
  };

  const toggleSection = (courseId) => {
    activeCourseId.value = courseId;
    openSections.value = openSections.value.includes(courseId) 
      ? openSections.value.filter(id => id !== courseId) 
      : [courseId];
  };

  const setActiveItem = (courseId, item) => {
    activeCourseId.value = courseId;
    activeItemId.value = item.id;
    
    const tabSlug = item.text.toLowerCase().replace(/\s+/g, '-');
    const role = route.params.role || 'student';

    // ONLY pass params that exist in index.js paths
    router.push({ 
      name: `${role}-${tabSlug}`, 
      params: { 
        role: role, 
        course: courseId 
      } 
    });
  };

  onMounted(syncState);
  watch(() => route.path, syncState);

  return { 
    openSections, 
    activeCourseId, 
    isItemActive, 
    toggleSection, 
    setActiveItem 
  };
}