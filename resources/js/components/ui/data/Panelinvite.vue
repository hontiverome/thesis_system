<template>
  <div class="panel-invitation-card">
    <h3 class="card-title">{{ title }}</h3>
    
    <template v-if="invitations.length > 0">
      <ul class="invitation-list">
        <li 
          v-for="(item, index) in visibleInvitations" 
          :key="item.id || index" 
          class="invitation-item"
        >
          <div class="left-section">
            <svg 
              xmlns="http://www.w3.org/2000/svg" 
              viewBox="0 0 24 24" 
              class="star-icon"
              :class="item.status?.toLowerCase()"
            >
              <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.007z" clip-rule="evenodd" />
            </svg>
            <span class="professor-name">{{ item.name }}</span>
          </div>
          
          <div class="right-section">
            <span class="status-text">{{ formatStatus(item.status) }}</span>
          </div>
        </li>
      </ul>
      
      <div v-if="invitations.length > 4" class="card-footer">
        <button type="button" class="more-btn" @click="showPopup = true">
          ....more
        </button>
      </div>
    </template>

    <div v-else class="empty-state">
      <p>Still waiting for professors to accept or reject...</p>
    </div>

    <Transition name="fade">
      <div v-if="showPopup" class="modal-overlay" @click.self="showPopup = false">
        <div class="modal-content">
          <div class="modal-header">
            <h3>All Invitations</h3>
            <button class="close-btn" @click="showPopup = false">&times;</button>
          </div>
          <div class="modal-body">
            <div v-for="(item, index) in invitations" :key="item.id || index" class="invitation-item">
              <div class="left-section">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="star-icon" :class="item.status?.toLowerCase()">
                  <path d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.007z" />
                </svg>
                <span class="professor-name">{{ item.name }}</span>
              </div>
              <span class="status-text">{{ formatStatus(item.status) }}</span>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  title: {
    type: String,
    default: 'Panel Invitation'
  },
  invitations: {
    type: Array,
    required: true,
    default: () => []
  }
});

const showPopup = ref(false);

// Logic: Show first 4 items on the card
const visibleInvitations = computed(() => {
  return props.invitations.slice(0, 4);
});

// Logic: Capitalize status or default to Pending
const formatStatus = (status) => {
  if (!status) return 'Pending';
  return status.charAt(0).toUpperCase() + status.slice(1).toLowerCase();
};
</script>

<style scoped>
.panel-invitation-card {
  background: #fff;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
  min-width: 300px;
}

.card-title {
  margin: 0 0 15px 0;
  font-size: 1.1rem;
  font-weight: 700;
  color: #111827;
}

.invitation-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.invitation-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 0;
  border-bottom: 1px solid #f3f4f6;
}

.invitation-item:last-child { border-bottom: none; }

.left-section {
  display: flex;
  align-items: center;
  gap: 10px;
}

/* Star Colors based on Status Class */
.star-icon {
  width: 18px;
  height: 18px;
  fill: #E5E7EB; /* Default Gray (Pending) */
  transition: fill 0.2s;
}
.star-icon.accepted { fill: #22C55E; } /* Green */
.star-icon.declined, .star-icon.rejected { fill: #EF4444; } /* Red */

.professor-name {
  font-size: 0.95rem;
  font-weight: 500;
  color: #374151;
}

.status-text {
  font-size: 0.85rem;
  color: #6B7280;
}

.more-btn {
  background: none;
  border: none;
  color: #6B7280;
  cursor: pointer;
  font-weight: 600;
  margin-top: 10px;
  font-size: 0.85rem;
}

.more-btn:hover {
  color: #374151;
}

/* Empty State Styling */
.empty-state {
  padding: 30px 10px;
  text-align: center;
}

.empty-state p {
  color: #9CA3AF;
  font-size: 0.9rem;
  font-style: italic;
  margin: 0;
}

/* Modal / Popup Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  backdrop-filter: blur(2px);
}

.modal-content {
  background: white;
  padding: 24px;
  border-radius: 12px;
  width: 90%;
  max-width: 450px;
  max-height: 70vh;
  overflow-y: auto;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
  border-bottom: 1px solid #f3f4f6;
  padding-bottom: 10px;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  line-height: 1;
  cursor: pointer;
  color: #9CA3AF;
}

/* Animations */
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>