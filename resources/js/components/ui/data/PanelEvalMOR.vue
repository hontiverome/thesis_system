<template>
  <div class="panel-card">
    <h2 class="panel-title">Panel Evaluation</h2>

    <div class="evaluation-list">
      <div 
        v-for="prof in evaluationData" 
        :key="prof.id" 
        class="evaluator-row"
      >
        <div class="info-group">
          <svg 
            class="star-icon" 
            :class="{ 'is-approved': prof.status?.toLowerCase() === 'approved' }"
            viewBox="0 0 24 24" 
            fill="currentColor"
          >
            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
          </svg>
          <span class="professor-name">{{ prof.name }}</span>
        </div>
        
        <span class="status-text">{{ prof.status || 'None' }}</span>
      </div>
    </div>

    <div class="actions">
      <button @click="$emit('open-form')" class="evaluation-btn">
        <span>EVALUATION FORM</span>
        <svg class="chevron-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
          <path d="M9 5l7 7-7 7" />
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
/**
 * PROPS
 * Defaulting all statuses to 'None' as requested.
 */
const props = defineProps({
  evaluationData: {
    type: Array,
    default: () => [
      { id: 1, name: 'Professor 1', status: 'None' },
      { id: 2, name: 'Professor 2', status: 'None' },
      { id: 3, name: 'Professor 3', status: 'None' }
    ]
  }
});

defineEmits(['open-form']);
</script>

<style scoped>
.panel-card {
  max-width: 500px;
  background: #ffffff;
  padding: 2rem;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

.panel-title {
  font-size: 2.8rem;
  font-weight: 800;
  margin-bottom: 2rem;
  color: #000;
  letter-spacing: -1px;
}

.evaluation-list {
  margin-bottom: 2.5rem;
}

.evaluator-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.2rem 0;
  border-bottom: 1px solid #f5f5f5;
}

.evaluator-row:last-child {
  border-bottom: none;
}

.info-group {
  display: flex;
  align-items: center;
  gap: 1.2rem;
}

.star-icon {
  width: 24px;
  height: 24px;
  color: #E48319; /* Default Orange requested */
}

.star-icon.is-approved {
  color: #27ae60; /* Green for when data eventually updates to Approved */
}

.professor-name {
  font-size: 1.2rem;
  color: #333;
}

.status-text {
  color: #a0a0a0;
  font-size: 1rem;
}

.actions {
  display: flex;
  justify-content: flex-end;
}

.evaluation-btn {
  background-color: #6d0000; /* Dark Maroon from screenshot */
  color: white;
  border: none;
  padding: 0.8rem 1.8rem;
  border-radius: 8px; /* Square-ish rounded look from screenshot */
  display: flex;
  align-items: center;
  gap: 1.5rem;
  cursor: pointer;
  font-weight: bold;
  letter-spacing: 0.5px;
  transition: opacity 0.2s;
}

.evaluation-btn:hover {
  opacity: 0.9;
}

.chevron-icon {
  width: 16px;
  height: 16px;
}
</style>