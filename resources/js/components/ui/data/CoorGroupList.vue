<template>
  <div class="directory-container">
    <div class="header-actions">
      <div class="filter-group">
        <slot name="filters"></slot>
      </div>
    </div>


    <div class="table-wrapper">
      <div v-if="loading" class="state-container">
        <Icon icon="eos-icons:loading" width="40" color="#a33131" />
        <p>Fetching thesis records...</p>
      </div>


      <div v-else-if="items.length === 0" class="state-container">
        <Icon icon="tabler:database-off" width="40" color="#ccc" />
        <p>No thesis records found.</p>
      </div>


      <table v-else class="data-table">
        <thead>
          <tr>
            <th class="text-center">Group Code</th>
            <th>Title</th>
            <th>Adviser</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in items" :key="item.id" class="table-row">
            <td class="code-cell text-center">{{ item.code }}</td>
            <td class="title-cell">{{ item.title }}</td>
            <td>{{ item.adviser }}</td>
            <td>
              <span :class="['status-text', item.status.toLowerCase()]">
                {{ item.status }}...
              </span>
            </td>
            <td class="action-cell">
              <div class="menu-container">
                <button class="menu-btn" @click.stop="toggleMenu(item.id)">
                  <Icon icon="bi:three-dots-vertical" />
                </button>


                <transition name="fade">
                  <div v-if="activeMenu === item.id" class="dropdown-card">
                    <button @click="$emit('view', item)">View Details</button>
                    <button @click="$emit('edit', item)">Edit Info</button>
                    <hr />
                    <button @click="$emit('delete', item)" class="text-red">Delete</button>
                  </div>
                </transition>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Icon } from '@iconify/vue';


// PROPS: These allow devs to pass data and state into the component
const props = defineProps({
  items: {
    type: Array,
    required: true,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  }
});


// EMITS: This tells the parent page what action was clicked
defineEmits(['view', 'edit', 'delete']);


const activeMenu = ref(null);


const toggleMenu = (id) => {
  activeMenu.value = activeMenu.value === id ? null : id;
};


const closeMenu = () => { activeMenu.value = null; };
onMounted(() => window.addEventListener('click', closeMenu));
onUnmounted(() => window.removeEventListener('click', closeMenu));
</script>


<style scoped>
/* Consistently styled with your previous components */
.directory-container { padding: 40px; background-color: #ffffff; }
.header-actions { display: flex; margin-bottom: 25px; }
.filter-group { display: flex; gap: 15px; width: 100%; }


.table-wrapper { overflow-x: auto; min-height: 200px; }
.data-table { width: 100%; border-collapse: collapse; }


.data-table th {
  text-align: left;
  padding: 15px 12px;
  border-bottom: 2px solid #f1f1f1;
  color: #333;
  font-size: 0.95rem;
  font-weight: 700;
}


.data-table td {
  padding: 15px 12px;
  border-bottom: 1px solid #f1f1f1;
  color: #555;
  font-size: 0.9rem;
}


.state-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 60px;
  color: #999;
}


/* Status Colors */
.status-text { font-weight: 600; font-size: 0.85rem; }
.status-text.waiting { color: #2c5282; }
.status-text.failed { color: #9b2c2c; }
.status-text.passed { color: #2c5282; }


/* Menu Styling */
.menu-container { position: relative; text-align: right; }
.menu-btn { background: none; border: none; cursor: pointer; color: #444; font-size: 1.2rem; padding: 5px; }


.dropdown-card {
  position: absolute;
  right: 0;
  top: 100%;
  background: white;
  border: 1px solid #eee;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  z-index: 100;
  width: 160px;
  padding: 8px 0;
}


.dropdown-card button {
  display: block;
  width: 100%;
  padding: 10px 15px;
  text-align: left;
  border: none;
  background: none;
  cursor: pointer;
  font-size: 0.85rem;
  color: #444;
}


.dropdown-card button:hover { background: #f1f1f1; color: #a33131; }
.text-red { color: #a33131 !important; }
.text-center { text-align: center; }


.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
