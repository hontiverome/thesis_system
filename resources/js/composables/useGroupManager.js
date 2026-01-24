// composables/useGroupManager.js
import { ref, computed, onMounted, onUnmounted } from 'vue';

export function useGroupManager(studentRegistry) {
  const activeTab = ref('groups');
  const groupSubView = ref('list');
  const activeDropdown = ref(null);

  const groupForm = ref({ code: '', leader: '', members: [] });
  const groups = ref([]);

  const availableLeaders = computed(() =>
    studentRegistry.value.filter(s => !groupForm.value.members.includes(s))
  );

  const availableMembers = computed(() =>
    studentRegistry.value.filter(s => s !== groupForm.value.leader)
  );

  const isGroupFormValid = computed(() =>
    groupForm.value.code &&
    groupForm.value.leader &&
    groupForm.value.members.length > 0
  );

  const toggleDropdown = (t) => {
    activeDropdown.value = activeDropdown.value === t ? null : t;
  };

  const selectLeader = (n) => {
    groupForm.value.leader = n;
    activeDropdown.value = null;
  };

  const addGroup = () => {
    if (!isGroupFormValid.value) return;
    groups.value.push({ ...groupForm.value });
    groupForm.value = { code: '', leader: '', members: [] };
  };

  const handleOutsideClick = (e) => {
    if (!e.target.closest('.custom-dropdown-container')) {
      activeDropdown.value = null;
    }
  };

  onMounted(() => document.addEventListener('click', handleOutsideClick));
  onUnmounted(() => document.removeEventListener('click', handleOutsideClick));

  return {
    activeTab,
    groupSubView,
    activeDropdown,
    groupForm,
    groups,
    availableLeaders,
    availableMembers,
    isGroupFormValid,
    toggleDropdown,
    selectLeader,
    addGroup
  };
}
