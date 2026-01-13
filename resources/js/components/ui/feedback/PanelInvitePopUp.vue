<template>
  <div class="modal-overlay" @click.self="$emit('close')">
    <div class="panel-invitation-popup">
      <button class="close-btn" @click="$emit('close')" aria-label="Close modal">×</button>
      
      <h2 class="popup-title">{{ title }}</h2>
      
      <div class="list-header">
        <span class="header-item prof-col">{{ columnOne }}</span>
        <span class="header-item date-col">{{ columnTwo }}</span>
        <span class="header-item status-col">{{ columnThree }}</span>
      </div>
      
      <ul class="invitation-list">
        <li 
          v-for="(item, index) in invitations" 
          :key="item.id || index" 
          class="invitation-item"
        >
          <div class="professor-info prof-col">
            <svg 
              xmlns="http://www.w3.org/2000/svg" 
              viewBox="0 0 24 24" 
              fill="currentColor" 
              class="star-icon"
              :class="item.status?.toLowerCase()"
            >
              <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.007z" clip-rule="evenodd" />
            </svg>
            <span class="professor-name">{{ item.name }}</span>
          </div>
          
          <div class="date-invited date-col">
            {{ item.date || '---' }}
          </div>
          
          <div class="status-text status-col">
            {{ item.status }}
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  // Title of the Popup
  title: {
    type: String,
    default: 'Panel Invitation'
  },
  // Table Headers
  columnOne: { type: String, default: 'PROFESSOR' },
  columnTwo: { type: String, default: 'DATE INVITED' },
  columnThree: { type: String, default: 'STATUS' },
  // The Data
  invitations: {
    type: Array,
    required: true,
    default: () => []
  }
});

defineEmits(['close']);
</script>

<style scoped>
/* Styles remain exactly the same as the previous version */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5); 
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  backdrop-filter: blur(2px);
}

.panel-invitation-popup {
  background-color: #FFFFFF;
  border-radius: 20px;
  padding: 35px;
  width: 750px; 
  max-width: 90%;
  max-height: 85vh;
  overflow-y: auto;
  box-shadow: 0 15px 40px rgba(0,0,0,0.2);
  font-family: 'Arial', sans-serif;
  position: relative;
}

.close-btn {
  position: absolute;
  top: 20px;
  right: 20px;
  width: 32px;
  height: 32px;
  background-color: #FFC1C1;
  color: #FFFFFF;
  border: none;
  border-radius: 50%;
  font-size: 1.4rem;
  line-height: 1;
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;
}

.popup-title {
  font-size: 2.2rem;
  font-weight: 900;
  text-align: center;
  margin-bottom: 35px;
  color: #000;
  text-transform: uppercase;
}

.list-header {
  display: flex;
  padding: 0 20px 15px;
  font-weight: 800;
  color: #000;
  font-size: 0.85rem;
  border-bottom: 2px solid #EEE;
}

.prof-col { flex: 2.5; text-align: left; }
.date-col { flex: 1.5; text-align: center; }
.status-col { flex: 1; text-align: right; }

.invitation-list {
  list-style: none;
  padding: 0;
  margin: 10px 0 0;
}

.invitation-item {
  display: flex;
  align-items: center;
  padding: 18px 20px;
  border-radius: 8px;
}

.invitation-item:nth-child(even) { background-color: #F9FAFB; }

.professor-info {
  display: flex;
  align-items: center;
  gap: 15px;
}

.professor-name {
  font-size: 1rem;
  font-weight: 600;
}

.date-invited, .status-text {
  color: #6B7280;
  font-size: 0.9rem;
}

.star-icon { width: 22px; height: 22px; color: #D1D5DB; }
.star-icon.approved { color: #009712; }
.star-icon.declined { color: #C71212; }
</style>