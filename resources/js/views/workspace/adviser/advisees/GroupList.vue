<template>
  <div class="group-list-container">
    <h2 class="form-title">Create a Group</h2>
    
    <div class="create-grid">
      <div class="input-wrapper">
        <input 
          ref="groupCodeInput" 
          v-model="form.code" 
          class="custom-input" 
          placeholder="Group Code" 
        />
      </div>

      <div class="input-wrapper custom-dropdown-container">
        <div class="custom-select-trigger" @click="toggleDropdown('leader')" :class="{ 'is-active': activeDropdown === 'leader' }">
          <span :class="form.leader ? 'selected-text' : 'placeholder'">{{ form.leader || 'Assign a leader' }}</span>
          <Icon icon="lucide:chevron-down" class="dropdown-icon" />
        </div>
        <div v-if="activeDropdown === 'leader'" class="dropdown-menu">
          <div v-for="item in availableLeaders" :key="item" class="dropdown-item single-item" @click="selectLeader(item)">{{ item }}</div>
          <div v-if="availableLeaders.length === 0" class="dropdown-item single-item" style="color: #94a3b8; font-style: italic;">
            No more student to assign
          </div>
        </div>
      </div>

      <div class="input-wrapper custom-dropdown-container">
        <div class="custom-select-trigger" @click="toggleDropdown('members')" :class="{ 'is-active': activeDropdown === 'members' }">
          <span :class="form.member.length ? 'selected-text' : 'placeholder'">{{ form.member.join(', ') || 'Choose members' }}</span>
          <Icon icon="lucide:chevron-down" class="dropdown-icon" />
        </div>
        <div v-if="activeDropdown === 'members'" class="dropdown-menu">
          <label v-for="item in availableMembers" :key="item" class="dropdown-item checkbox-item">
            <input type="checkbox" :value="item" v-model="form.member" />
            <span class="member-name">{{ item }}</span>
          </label>
          <div v-if="availableMembers.length === 0" class="dropdown-item single-item" style="color: #94a3b8; font-style: italic;">
            No more student to assign
          </div>
        </div>
      </div>

      <button @click="handleAddGroup" class="btn-create" :disabled="!isFormValid">Create</button>
    </div>

    <div class="info-bar">
      <div v-for="(val, lab) in { 'CLASS SECTION': classSection, 'NO. OF GROUPS': existingGroups.length }" :key="lab" class="info-item">
        <span class="label">{{ lab }}:</span> <span class="value">{{ val }}</span>
      </div>
    </div>

    <div class="table-container">
      <table class="group-table">
        <thead>
          <tr>
            <th v-for="h in ['Code', 'Leader', 'Members']" :key="h" :class="`col-${h.toLowerCase()}`">Group {{ h }}</th>
            <th class="col-search">
              <div class="search-input-container">
                <Icon icon="lucide:search" class="search-icon" />
                <input type="text" placeholder="Search Group Code" @input="$emit('search', $event.target.value)" />
              </div>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(group, i) in existingGroups" :key="i">
            <td class="col-text">{{ group.code }}</td>
            <td class="col-text">{{ group.leader }}</td>
            <td class="members-cell">
              <div class="stacked-members">
                <div v-for="m in (Array.isArray(group.member) ? group.member : [group.member])" :key="m">{{ m }}</div>
              </div>
            </td>
            <td class="action-cell">
              <div class="action-wrapper">
                <button class="btn-action" @click="$emit('edit-group', group)"><Icon icon="lucide:square-pen" /></button>
                <button class="btn-action" @click="$emit('delete-group', group.id ?? i)"><Icon icon="lucide:trash-2" /></button>
              </div>
            </td>
          </tr>
          <tr v-if="existingGroups.length === 0">
            <td colspan="4" class="empty-state-cell">
              No groups yet, <span class="create-link" @click="focusInput">Create one.</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Icon } from '@iconify/vue';

const props = defineProps({
  classSection: { type: String, default: 'N/A' },
  leaders: { type: Array, default: () => [] },
  members: { type: Array, default: () => [] },
  existingGroups: { type: Array, default: () => [] }
});

const emit = defineEmits(['create-group', 'delete-group', 'edit-group', 'search']);

// DOM Reference for the input
const groupCodeInput = ref(null);
const activeDropdown = ref(null);
const form = ref({ code: '', leader: '', member: [] });

// Logic to focus the input field
const focusInput = () => {
  if (groupCodeInput.value) {
    groupCodeInput.value.focus();
    // Optional: Smooth scroll to top if the table is long
    groupCodeInput.value.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
};

const assignedInTable = computed(() => {
  return new Set(props.existingGroups.flatMap(g => [g.leader, ...(Array.isArray(g.member) ? g.member : [g.member])]));
});

const availableLeaders = computed(() => {
  return props.leaders.filter(s => !assignedInTable.value.has(s) && !form.value.member.includes(s));
});

const availableMembers = computed(() => {
  return props.members.filter(s => !assignedInTable.value.has(s) && form.value.leader !== s);
});

const toggleDropdown = (t) => activeDropdown.value = activeDropdown.value === t ? null : t;
const selectLeader = (n) => { form.value.leader = n; activeDropdown.value = null; };
const isFormValid = computed(() => form.value.code && form.value.leader && form.value.member.length > 0);

const handleAddGroup = () => {
  if (isFormValid.value) {
    emit('create-group', { ...form.value });
    form.value = { code: '', leader: '', member: [] };
    activeDropdown.value = null;
  }
};

const handleOut = (e) => { if (!e.target.closest('.custom-dropdown-container')) activeDropdown.value = null; };
onMounted(() => document.addEventListener('click', handleOut));
onUnmounted(() => document.removeEventListener('click', handleOut));
</script>

<style scoped>
.group-list-container { width: 100%; font-family: sans-serif; }
.form-title { font-size: 16px; font-weight: 700; margin-bottom: 12px; }
.create-grid { display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 15px; margin-bottom: 30px; }

.custom-input, .custom-select-trigger { 
  width: 100%; 
  padding: 12px 15px; 
  border: 3px solid transparent; 
  box-shadow: 0 0 0 1px #cbd5e1; 
  border-radius: 10px; 
  background: #fff; 
  font-size: 14px; 
  min-height: 46px; 
  box-sizing: border-box; 
  display: flex; 
  justify-content: space-between; 
  align-items: center; 
  transition: border-color 0.2s;
}

.custom-select-trigger.is-active, .custom-input:focus { 
  border-color: #E8B931; 
  outline: none; 
  box-shadow: none; 
}

.placeholder { color: #94a3b8; }
.dropdown-icon { color: #94a3b8; }
.custom-dropdown-container { position: relative; }

.dropdown-menu { 
  position: absolute; 
  top: 105%; 
  left: 0; 
  width: 100%; 
  max-height: 200px; 
  overflow-y: auto; 
  background: #fff; 
  border: 1px solid #cbd5e1; 
  border-radius: 10px; 
  z-index: 50; 
  padding: 5px 0; 
  box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
}

.dropdown-item { padding: 10px 15px; font-size: 14px; cursor: pointer; }
.dropdown-item:hover { background-color: #f8fafc; }
.checkbox-item { display: flex; align-items: center; gap: 10px; }

.btn-create { background: #E8B931; color: #fff; border: none; height: 46px; padding: 0 35px; border-radius: 10px; font-weight: 700; cursor: pointer; }
.btn-create:disabled { background: #e2e8f0; cursor: not-allowed; }

.info-bar { display: flex; gap: 40px; margin-bottom: 25px; font-size: 14px; }
.info-item .label { font-weight: 700; margin-right: 5px; }

.table-container { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; }
.group-table { width: 100%; border-collapse: collapse; table-layout: fixed; }

th { padding: 18px 20px; text-align: center; font-weight: 700; color: #1e293b; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
td { padding: 20px; text-align: center; border-bottom: 1px solid #f1f5f9; vertical-align: middle; color: #334155; font-size: 14px; }

.empty-state-cell { padding: 40px !important; color: #94a3b8; font-style: italic; text-align: center; }

/* The Yellow "Create one" Link */
.create-link {
  color: #E8B931;
  font-weight: 700;
  cursor: pointer;
  text-decoration: underline;
  font-style: normal;
  margin-left: 4px;
}
.create-link:hover { opacity: 0.8; }

.stacked-members { display: inline-flex; flex-direction: column; gap: 6px; text-align: center; }
.action-wrapper { display: flex; justify-content: center; align-items: center; gap: 15px; }
.btn-action { background: none; border: none; color: #94a3b8; cursor: pointer; font-size: 20px; transition: color 0.2s; }
.btn-action:hover { color: #64748b; }

.search-input-container { 
  display: flex; 
  align-items: center; 
  background: #fff; 
  border: 1px solid #cbd5e1; 
  border-radius: 20px; 
  padding: 5px 12px; 
  gap: 8px; 
}
.search-input-container input { border: none; outline: none; font-size: 12px; width: 100%; }

.col-code { width: 15%; }
.col-leader { width: 30%; }
.col-members { width: 35%; }
.col-search { width: 20%; }
</style>