<template>
  <div class="modal-overlay" @click.self="$emit('close')">
    <div class="modal-card">
      <h3 :style="{ color: actionType === 'accept' ? '#065f27' : '#7f0000' }">
        Confirm {{ actionType === 'accept' ? 'Acceptance' : 'Rejection' }}
      </h3>
      <p class="subtitle">Research: {{ itemTitle }}</p>
      
      <div class="input-group">
        <label>Reason / Comment:</label>
        <textarea v-model="commentText" placeholder="Provide feedback..."></textarea>
      </div>

      <div class="modal-footer">
        <button class="btn-cancel" @click="$emit('close')">Cancel</button>
        <button 
          :class="actionType === 'accept' ? 'confirm-accept' : 'confirm-reject'"
          @click="$emit('confirm', commentText)"
        >
          Confirm Decision
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

defineProps({
  actionType: String,
  itemTitle: String
});

defineEmits(['close', 'confirm']);

const commentText = ref("");
</script>

<style scoped>
.modal-overlay {
  position: fixed; top: 0; left: 0; width: 100%; height: 100%;
  background: rgba(0, 0, 0, 0.6); display: flex; align-items: center;
  justify-content: center; z-index: 1000; font-family: sans-serif;
}
.modal-card {
  background: white; padding: 25px; border-radius: 12px;
  width: 400px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}
h3 { margin-top: 0; text-transform: uppercase; font-size: 18px; }
.subtitle { font-size: 14px; color: #64748b; margin-bottom: 20px; }
.input-group label { display: block; font-size: 13px; font-weight: 700; margin-bottom: 8px; }
textarea {
  width: 100%; height: 100px; padding: 10px; border: 1px solid #cbd5e1;
  border-radius: 6px; resize: none; box-sizing: border-box;
}
.modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
button { padding: 10px 16px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; }
.btn-cancel { background: #f1f5f9; color: #475569; }
.confirm-accept { background: #065f27; color: white; }
.confirm-reject { background: #7f0000; color: white; }
</style>