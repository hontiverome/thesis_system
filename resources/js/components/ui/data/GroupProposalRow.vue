<template>
  <BaseCard class="p-0 mb-4 overflow-hidden">
    <div class="flex items-start">
      <div class="w-1/4 p-4 border-r border-gray-200 flex flex-col justify-between h-full bg-gray-50">
        <div class="text-center">
          <p class="text-sm font-medium text-gray-500">Group Code</p>
          <p class="text-3xl font-bold text-gray-900 mt-1">{{ groupCode }} [cite: 116, 127]</p>
        </div>
        
        <div class="mt-4 text-center">
            <StatusCounter :count="acceptedCount" :max="totalProposals" />
        </div>
      </div>

      <div class="w-3/4 p-4">
        <h4 class="text-lg font-semibold text-gray-700 mb-3">Research Titles ({{ proposals.length }})</h4>
        
        <div v-for="proposal in proposals" :key="proposal.id">
          <ProposalItem 
            :title="proposal.title" 
            mode="adviser" 
            @accept="$emit('accept-proposal', proposal.id)" 
            @reject="$emit('reject-proposal', proposal.id)" 
          />
        </div>
      </div>
    </div>
  </BaseCard>
</template>

<script setup>
import { computed } from 'vue';
import BaseCard from '../core/BaseCard.vue';
import ProposalItem from './ProposalItem.vue';
import StatusCounter from './StatusCounter.vue';

const props = defineProps({
  groupCode: {
    type: String,
    required: true // e.g., "3301" [cite: 127]
  },
  proposals: {
    type: Array, // Array of proposal objects { id, title, status }
    required: true
  }
});

const acceptedCount = computed(() => {
  // Logic to calculate how many proposals are accepted, for the StatusCounter
  // Placeholder: in a real app, you'd check a 'votes' or 'status' property.
  return props.proposals.filter(p => p.status === 'accepted').length;
});

const totalProposals = computed(() => props.proposals.length);

defineEmits(['accept-proposal', 'reject-proposal']);
</script>