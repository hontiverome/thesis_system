<template>
  <div class="submissions-container">
    <div v-if="classSection" class="header-container-sub">
      <div class="class-info">
        <span class="label">CLASS SECTION:</span> 
        <span class="value">{{ classSection }}</span>
      </div>
    </div>

    <div class="table-container shadow-none">
      <table class="submission-table">
        <thead>
          <tr>
            <th class="col-code sub-header text-center">GROUP CODE</th>
            <th class="col-research sub-header text-center">RESEARCH TITLES</th>
            <th class="col-status sub-header text-center">STATUS</th>
            <th class="col-filter sub-header">
              <div class="filter-wrapper">
                <select 
                  class="filter-select" 
                  @change="handleFilterChange"
                >
                  <option value="">Show All Submissions</option>
                  <option v-for="opt in filterOptions" :key="opt" :value="opt">{{ opt }}</option>
                </select>
              </div>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="group in filteredGroups" :key="group.code">
            <td class="code-cell">
              <div class="code-text">{{ group.code }}</div>
            </td>
            <td colspan="3" class="content-area-cell">
              <div 
                v-for="(item, index) in group.researchItems" 
                :key="index" 
                class="submission-grid-row"
              >
                <div class="research-col">
                  <div class="research-tag" :class="{ 'is-empty': !item.id }">
                    {{ item.title || 'No Research Title Submitted' }}
                  </div>
                </div>

                <div class="status-col">
                  <div v-if="item.status === 'Accepted'" class="status-pill is-accepted">
                    <Icon icon="lucide:check-circle" class="status-icon" />
                    <span class="status-text">Accepted</span>
                  </div>

                  <div v-else-if="item.status === 'Rejected'" class="status-pill is-rejected">
                    <Icon icon="lucide:x-circle" class="status-icon" />
                    <span class="status-text">Rejected</span>
                  </div>

                  <div v-else class="status-pill">
                    <span class="count-text">
                      {{ item.count || '0' }}/{{ item.max || 0 }}
                    </span>
                    <Icon icon="mdi:account" class="user-icon" />
                  </div>
                </div>

                <div class="actions-col">
                  <div v-if="showActions" class="action-buttons">
                    <template v-if="!item.status">
                      <button 
                        class="btn-accept" 
                        :disabled="!item.id"
                        @click="triggerAction('accept', group.code, item)"
                      >
                        <Icon icon="lucide:check" class="btn-icon" /> | accept
                      </button>
                      <button 
                        class="btn-reject" 
                        :disabled="!item.id"
                        @click="triggerAction('reject', group.code, item)"
                      >
                        <Icon icon="lucide:x" class="btn-icon" /> | reject
                      </button>
                    </template>
                    
                    <div v-else class="centered-decision">
                       <div v-if="item.status === 'Accepted'" class="btn-accept is-static">
                          <Icon icon="lucide:check" class="btn-icon" /> | accepted
                       </div>
                       <div v-if="item.status === 'Rejected'" class="btn-reject is-static">
                          <Icon icon="lucide:x" class="btn-icon" /> | rejected
                       </div>
                    </div>
                  </div>
                  <div v-else class="view-only-text">View Only</div>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <Comment 
      v-if="showPopup" 
      :action-type="pendingAction.type"
      :item-title="pendingAction.item?.title"
      @close="showPopup = false"
      @confirm="onPopupConfirm"
    />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Icon } from '@iconify/vue';
import Comment from './Comment.vue';

const props = defineProps({
  // Setting default to null makes it optional
  classSection: { type: String, default: null },
  submissions: { type: Array, default: () => [] },
  filterOptions: { type: Array, default: () => ['Accepted', 'Rejected'] },
  // Allows other devs to hide the buttons entirely
  showActions: { type: Boolean, default: true }
});

const emit = defineEmits(['accept', 'reject', 'filter-change']);

const currentFilter = ref('');
const showPopup = ref(false);
const pendingAction = ref({ type: '', code: '', item: null });

const filteredGroups = computed(() => {
  if (!props.submissions || props.submissions.length === 0) {
    return [{ code: '----', researchItems: [{}] }];
  }
  if (!currentFilter.value) return props.submissions;

  return props.submissions.map(group => ({
    ...group,
    researchItems: group.researchItems.filter(item => item.status === currentFilter.value)
  })).filter(group => group.researchItems.length > 0);
});

const handleFilterChange = (event) => {
  currentFilter.value = event.target.value;
  emit('filter-change', event.target.value);
};

const triggerAction = (type, code, item) => {
  pendingAction.value = { type, code, item };
  showPopup.value = true;
};

const onPopupConfirm = (commentText) => {
  emit(pendingAction.value.type, {
    code: pendingAction.value.code,
    item: pendingAction.value.item,
    comment: commentText
  });
  showPopup.value = false;
};
</script>

<style scoped>
.submissions-container { width: 100%; font-family: sans-serif; }
.header-container-sub { margin-bottom: 20px; }
.class-info .label { color: #333; font-weight: 700; margin-right: 8px; }
.class-info .value { color: #333; font-weight: 400; }

.table-container { 
  background: white; border-radius: 12px; 
  border: 1px solid #e2e8f0; overflow: hidden; 
}

.submission-table { 
  width: 100%; border-collapse: collapse; table-layout: fixed; 
}

.sub-header { 
  font-size: 16px !important; font-weight: 800 !important; 
  color: #1e293b !important; padding: 18px 15px !important; background: #f8fafc;
}

.text-center { text-align: center !important; }

.col-code { width: 12%; }
.col-research { width: 50%; }
.col-status { width: 15%; } 
.col-filter { width: 23%; padding-right: 25px; } 

.code-cell { 
  text-align: center; vertical-align: middle; 
  border-right: 2px solid #f1f5f9; padding: 20px 0; 
}

.code-text { font-weight: 700; font-size: 22px; color: #444; }
.content-area-cell { padding: 0; }

.submission-grid-row { 
  display: flex; align-items: center; 
  padding: 12px 0; border-bottom: 1px solid #f8fafc;
}

.submission-grid-row:last-child { border-bottom: none; }

.research-col { flex: 0 0 56.8%; display: flex; padding: 0 20px; } 
.status-col { flex: 0 0 17%; display: flex; justify-content: center; align-items: center; } 
.actions-col { flex: 0 0 26.2%; display: flex; justify-content: center; padding-right: 25px; }

.research-tag { 
  background: #dbdbdb; padding: 10px 16px; 
  border-radius: 4px; font-size: 13px; 
  color: #555; width: 100%;
  white-space: nowrap; overflow: hidden; 
  text-overflow: ellipsis; 
}

.research-tag.is-empty {
  background: #f1f5f9; color: #94a3b8;
  font-style: italic; border: 1px dashed #cbd5e1;
}

.status-pill { 
  background: #f1f5f9; padding: 8px 10px; 
  border-radius: 4px; display: flex; 
  align-items: center; gap: 6px; 
  min-width: 90px; justify-content: center;
  transition: all 0.3s ease;
}

.status-pill.is-accepted {
  background-color: #dcfce7; color: #166534;
  border: 1px solid #bbf7d0;
}

.status-pill.is-rejected {
  background-color: #fee2e2; color: #991b1b;
  border: 1px solid #fecaca;
}

.status-icon { font-size: 16px; }
.status-text { font-weight: 700; font-size: 11px; text-transform: uppercase; }

.user-icon { font-size: 18px; color: #000; }
.count-text { font-weight: 600; font-size: 13px; color: #475569; }

.action-buttons { display: flex; gap: 8px; align-items: center; }
.view-only-text { color: #94a3b8; font-size: 12px; font-style: italic; }

.btn-accept, .btn-reject { 
  border: none; border-radius: 4px; 
  padding: 8px 12px; color: white; 
  cursor: pointer; display: flex; 
  align-items: center; gap: 5px; 
  font-size: 13px; font-weight: 600; height: 38px;
}

.btn-accept { background-color: #065f27; }
.btn-reject { background-color: #7f0000; }

.btn-accept:disabled, .btn-reject:disabled {
  opacity: 0.2;
  cursor: not-allowed;
}

.filter-select { 
  padding: 8px 35px 8px 12px; border: 1px solid #cbd5e1; 
  border-radius: 8px; color: #94a3b8; 
  width: 100%; 
  background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3Cpath%3E%3C/svg%3E") no-repeat;
  background-position: calc(100% - 15px) center;
  background-size: 15px; appearance: none;
  font-size: 13px; height: 40px;
}

.filter-select:focus { border: 3px solid #EDC35E; color: #333; outline: none; }

.centered-decision { display: flex; justify-content: center; width: 100%; }
.is-static { cursor: default !important; pointer-events: none; opacity: 0.9; }
</style>