<template>
  <div class="thesis-wrapper">
    <header class="thesis-header">
      <h1 class="main-title">THESIS TITLE DEFENSE</h1>
      <div class="filter-section">
        <select class="filter-dropdown" v-model="selectedFilter">
          <option value="all">Group Code / Thesis Title</option>
          <option v-for="item in items" :key="item.id" :value="item.groupCode">
            {{ item.groupCode }} - {{ item.title }}
          </option>
        </select>
      </div>
    </header>

    <div class="table-container">
      <table class="thesis-table">
        <thead>
          <tr>
            <th>Group Code</th>
            <th>Title</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <template v-if="displayData.length > 0">
            <tr v-for="item in displayData" :key="item.id" :class="{ 'placeholder-row': item.isDefault }">
              <td class="group-code">{{ item.groupCode }}</td>
              <td class="thesis-title">{{ item.title }}</td>
              <td>
                <div class="status-container">
                  <button 
                    :class="['status-pill', item.status.toLowerCase()]"
                    @click="!item.isDefault && openStatusModal(item)"
                    :disabled="item.isDefault"
                  >
                    {{ item.status }}
                  </button>
                  <span v-if="item.hasUpdate && item.status === 'UPDATE'" class="status-dot"></span>
                </div>
              </td>
              <td class="action-cell">
                <button class="dots-btn" @click.stop="!item.isDefault && toggleMenu(item.id)">⋮</button>
                
                <div v-if="activeMenuId === item.id" class="dropdown-menu">
                  <button @click="emit('see-paper', item)">
                    <span class="star-icon">☆</span> See Paper
                  </button>
                  <button @click="emit('see-evaluation', item)">
                    <span class="star-icon">☆</span> See Evaluation Form
                  </button>
                </div>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>

    <Transition name="fade">
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content">
          <h2 class="modal-header-text">Update Result</h2>
          <p class="modal-subtext">Set the status for Group {{ selectedItem?.groupCode }}</p>
          <div class="modal-buttons">
            <button class="choice-btn pass-btn" @click="updateStatus('PASSED')">PASSED</button>
            <button class="choice-btn fail-btn" @click="updateStatus('FAILED')">FAILED</button>
            <button class="cancel-btn" @click="closeModal">Cancel</button>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  items: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['status-update', 'see-paper', 'see-evaluation']);

const selectedFilter = ref("all");
const activeMenuId = ref(null);
const showModal = ref(false);
const selectedItem = ref(null);

// COMPUTED LOGIC: Shows a default row if the prop is empty
const displayData = computed(() => {
  // 1. If devs put data, filter it and return
  if (props.items && props.items.length > 0) {
    if (selectedFilter.value === "all") return props.items;
    return props.items.filter(item => item.groupCode === selectedFilter.value);
  }

  // 2. If NO data, return this "Default Row"
  return [{
    id: 'default',
    groupCode: '----',
    title: 'No Thesis Proposals Loaded',
    status: 'UPDATE',
    hasUpdate: true,
    isDefault: true // Marker to disable clicks
  }];
});

const openStatusModal = (item) => {
  selectedItem.value = item;
  showModal.value = true;
};

const updateStatus = (newStatus) => {
  if (selectedItem.value) {
    emit('status-update', { id: selectedItem.value.id, status: newStatus });
  }
  closeModal();
};

const closeModal = () => {
  showModal.value = false;
  selectedItem.value = null;
};

const toggleMenu = (id) => {
  activeMenuId.value = activeMenuId.value === id ? null : id;
};

const closeMenus = () => (activeMenuId.value = null);
onMounted(() => window.addEventListener('click', closeMenus));
onUnmounted(() => window.removeEventListener('click', closeMenus));
</script>

<style scoped>
.thesis-wrapper { font-family: 'Inter', sans-serif; padding: 40px; color: #333; }
.thesis-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1.5px solid #ccc; padding-bottom: 10px; margin-bottom: 20px; }
.main-title { font-size: 1.5rem; letter-spacing: 2px; font-weight: 700; }
.filter-dropdown { padding: 8px 12px; border: 1px solid #ccc; border-radius: 6px; min-width: 240px; cursor: pointer; color: #555; }

.thesis-table { width: 100%; border-collapse: collapse; }
.thesis-table th { text-align: left; padding: 12px; color: #800000; font-weight: bold; font-size: 0.9rem; text-transform: uppercase; }
.thesis-table td { padding: 15px 12px; border-bottom: 1px solid #eee; }

/* Placeholder Styling */
.placeholder-row { opacity: 0.5; font-style: italic; }

.status-container { position: relative; display: inline-block; }
.status-pill { padding: 6px 0; width: 130px; border-radius: 20px; border: none; font-weight: 900; font-size: 0.75rem; cursor: pointer; color: white; }
.status-pill:disabled { cursor: not-allowed; }
.status-pill.update { background-color: #FFC107; }
.status-pill.passed { background-color: #28a745; }
.status-pill.failed { background-color: #dc3545; }

.status-dot { position: absolute; top: -3px; right: 2px; width: 10px; height: 10px; background-color: #FF5252; border-radius: 50%; border: 2px solid white; }

.action-cell { position: relative; text-align: right; width: 40px; }
.dots-btn { background: none; border: none; font-size: 1.4rem; cursor: pointer; }
.dropdown-menu { position: absolute; right: 0; top: 35px; background: white; width: 210px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.12); z-index: 100; }
.dropdown-menu button { width: 100%; padding: 12px 18px; text-align: left; border: none; background: none; cursor: pointer; font-size: 0.85rem; display: flex; align-items: center; }
.dropdown-menu button:hover { background-color: #f8f9fa; }
.star-icon { margin-right: 12px; font-size: 1rem; }

.modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); display: flex; justify-content: center; align-items: center; z-index: 1000; }
.modal-content { background: white; width: 350px; padding: 30px; border-radius: 12px; text-align: center; }
.choice-btn { width: 100%; padding: 14px; margin-bottom: 10px; border-radius: 8px; border: 1px solid #eee; font-weight: 800; cursor: pointer; }
.pass-btn:hover { border-color: #28a745; color: #28a745; }
.fail-btn:hover { border-color: #dc3545; color: #dc3545; }
.cancel-btn { width: 100%; padding: 14px; border: none; border-radius: 8px; background: #800000; color: white; font-weight: 800; cursor: pointer; margin-top: 5px; }

.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>