<template>
  <div class="card-wrapper">
    <div class="feedback-card">
      <div class="card-header">
        <div class="professor-badge">
          {{ profName }}
        </div>
        
        <div v-if="hasFeedback" class="status-badge" :class="statusClass">
          {{ status }}
        </div>
      </div>

      <div class="card-content">
        <p :class="['feedback-text', { 'is-truncated': !isExpanded }]">
          {{ text || 'Waiting for professor feedback...' }}
        </p>
        
        <div v-if="text.length > 120" class="more-container">
          <button class="more-btn" @click="isExpanded = !isExpanded">
            {{ isExpanded ? 'show less' : '...more' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  // Name of the professor
  profName: { 
    type: String, 
    required: true 
  },
  // The actual comment text
  text: { 
    type: String, 
    default: '' 
  },
  // The explicit status sent from the professor's UI ('Accepted' or 'Declined')
  status: { 
    type: String, 
    default: 'Pending' 
  }
});

const isExpanded = ref(false);

// Only show the status badge if there is actual text content
const hasFeedback = computed(() => {
  return props.text && props.text.trim().length > 0;
});

// Applies CSS classes based on the explicit status prop
const statusClass = computed(() => {
  const s = props.status.toLowerCase();
  if (s === 'accepted') return 'status-accepted';
  if (s === 'declined') return 'status-declined';
  return ''; // Default/Pending style
});
</script>

<style scoped>
.card-wrapper {
  width: 100%;
  margin-bottom: 20px;
}

.feedback-card {
  border: 1.5px solid #000;
  border-radius: 20px;
  padding: 20px;
  background-color: #fff;
  font-family: 'Courier New', Courier, monospace;
}

.card-header {
  display: flex;
  gap: 12px;
  margin-bottom: 15px;
  align-items: center;
}

.professor-badge {
  border: 1.5px solid #000;
  border-radius: 15px;
  padding: 8px 15px;
  flex-grow: 1;
  font-weight: 700;
  font-size: 0.95rem;
  background-color: #f9f9f9;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Status Badge Styling */
.status-badge {
  border: 1.5px solid #000;
  border-radius: 15px;
  padding: 6px 15px;
  font-size: 0.8rem;
  font-weight: 700;
  text-transform: uppercase;
  min-width: 100px;
  text-align: center;
}

.status-accepted {
  background-color: #E8F5E9;
  color: #2E7D32;
  border-color: #2E7D32;
}

.status-declined {
  background-color: #FFEBEE;
  color: #C62828;
  border-color: #C62828;
}

/* Content Styling */
.feedback-text {
  font-size: 0.95rem;
  line-height: 1.6;
  margin: 0;
  color: #000;
}

/* Truncation Logic: Limits to 3 lines */
.is-truncated {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.more-container {
  display: flex;
  justify-content: flex-end;
  margin-top: 8px;
}

.more-btn {
  background: none;
  border: none;
  font-size: 0.85rem;
  font-weight: 700;
  cursor: pointer;
  color: #888;
  padding: 4px 10px;
  border-radius: 5px;
}

.more-btn:hover {
  background-color: #f0f0f0;
  color: #000;
}
</style>