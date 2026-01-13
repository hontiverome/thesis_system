<template>
  <div class="thesis-card">
    <div class="accent-bar" :class="statusClass"></div>

    <div class="card-content">
      <div class="group-info">
        <div class="group-block">
          <p class="group-label">GROUP</p>
          <p class="group-code">{{ groupCode }}</p>
        </div>

        <div class="thesis-details">
          <h2 class="thesis-title" :title="thesisTitle">
            [{{ thesisTitle.toUpperCase() }}]
          </h2>
          <p class="presentation-date">{{ formattedDate }}</p>
        </div>
      </div>

      <div class="actions-container">
        <button 
          class="btn btn-details" 
          aria-label="View thesis details"
          @click="$emit('view-details')"
        >
          Details
        </button>

        <div class="choice-area" :class="{ 'is-decided': decision }">
          <button 
            v-if="!decision || decision === DECISION.ACCEPTED" 
            class="btn btn-accept" 
            :disabled="decision === DECISION.ACCEPTED"
            @click="handleDecision(DECISION.ACCEPTED)"
          >
            {{ decision === DECISION.ACCEPTED ? 'Accepted' : 'Accept' }}
          </button>

          <button 
            v-if="!decision || decision === DECISION.REJECTED" 
            class="btn btn-reject" 
            :disabled="decision === DECISION.REJECTED"
            @click="handleDecision(DECISION.REJECTED)"
          >
            {{ decision === DECISION.REJECTED ? 'Rejected' : 'Reject' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';

// 1. Define Constants for better maintainability
const DECISION = {
  ACCEPTED: 'accepted',
  REJECTED: 'rejected',
} as const;

type DecisionType = typeof DECISION[keyof typeof DECISION];

// 2. Explicit TypeScript Props
interface Props {
  groupCode?: string;
  thesisTitle?: string;
  dateOfPresentation?: string | Date;
  maxWidth?: string;
}

const props = withDefaults(defineProps<Props>(), {
  groupCode: 'TBD',
  thesisTitle: 'Untitled Thesis',
  dateOfPresentation: 'No Date Set',
  maxWidth: '850px'
});

// 3. Typed Emits
const emit = defineEmits<{
  (e: 'view-details'): void;
  (e: 'decision', status: DecisionType): void;
}>();

const decision = ref<DecisionType | null>(null);

// 4. Computed logic for dynamic styling
const statusClass = computed(() => {
  if (decision.value === DECISION.ACCEPTED) return 'status-accepted';
  if (decision.value === DECISION.REJECTED) return 'status-rejected';
  return '';
});

const formattedDate = computed(() => {
  if (props.dateOfPresentation instanceof Date) {
    return props.dateOfPresentation.toLocaleDateString(undefined, {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  }
  return props.dateOfPresentation;
});

const handleDecision = (type: DecisionType) => {
  if (decision.value) return;
  decision.value = type;
  emit('decision', type);
};
</script>

<style scoped>
/* Scoped CSS variables for easy theme changes */
.thesis-card {
  --green-primary: #1b5e20;
  --red-primary: #800000;
  --orange-primary: #e67e22;
  
  display: flex;
  background: #ffffff;
  border-radius: 4px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
  margin: 12px auto;
  width: 100%;
  max-width: v-bind(maxWidth);
  font-family: 'Inter', system-ui, sans-serif;
  overflow: hidden;
  transition: transform 0.2s ease;
}

.accent-bar {
  width: 6px;
  background-color: #bdc3c7; /* Default Neutral */
  transition: background-color 0.3s ease;
}

.accent-bar.status-accepted { background-color: var(--green-primary); }
.accent-bar.status-rejected { background-color: var(--red-primary); }

.card-content {
  display: flex;
  flex: 1;
  align-items: center;
  justify-content: space-between;
  padding: 14px 20px;
  min-width: 0;
}

.group-info {
  display: flex;
  align-items: center;
  gap: 24px;
  min-width: 0;
}

.group-block {
  min-width: 80px;
  border-right: 1px solid #eee;
}

.group-label {
  margin: 0;
  font-size: 0.7rem;
  color: #888;
  letter-spacing: 1px;
}

.group-code {
  margin: 0;
  font-size: 1rem;
  font-weight: 800;
  color: #2c3e50;
}

.thesis-details { min-width: 0; }

.thesis-title {
  margin: 0;
  font-size: 1.1rem;
  font-weight: 700;
  color: #1a1a1a;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.presentation-date {
  margin: 4px 0 0 0;
  font-size: 0.75rem;
  color: #666;
}

.actions-container {
  display: flex;
  align-items: center;
  gap: 12px;
}

.choice-area {
  display: flex;
  gap: 8px;
  min-width: 170px;
  justify-content: flex-end;
}

.btn {
  border: none;
  border-radius: 4px;
  padding: 8px 16px;
  font-weight: 600;
  font-size: 0.8rem;
  color: white;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn:disabled {
  cursor: default;
  opacity: 0.9;
}

.btn-details { background-color: var(--orange-primary); }
.btn-accept  { background-color: var(--green-primary); }
.btn-reject  { background-color: var(--red-primary); }

.btn:hover:not(:disabled) {
  filter: brightness(1.1);
  transform: translateY(-1px);
}

.is-decided .btn {
  flex: 1;
  text-align: center;
}

@media (max-width: 768px) {
  .card-content { flex-direction: column; align-items: stretch; }
  .group-block { border-right: none; border-bottom: 1px solid #eee; padding-bottom: 8px; margin-bottom: 8px; }
  .actions-container { margin-top: 16px; justify-content: space-between; }
  .choice-area { flex: 1; }
}
</style>