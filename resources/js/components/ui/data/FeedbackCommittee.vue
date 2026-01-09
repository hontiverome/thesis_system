<template>
  <div class="card-wrapper">
    <div class="feedback-card">
      <div class="card-header">
        <div class="professor-badge">
          {{ profName }}
        </div>
        
        <div class="status-badge">
          {{ status }}
        </div>
      </div>

      <div class="card-content">
        <p class="feedback-text">
          {{ text }}
        </p>
        
        <div class="more-container">
          <button class="more-btn" @click="$emit('expand')">.....more</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// This is what makes the code "Automatic" for other devs
defineProps({
  profName: { type: String, default: 'Professor Name' },
  status: { type: String, default: 'Accepted' },
  text: { type: String, default: 'No feedback provided yet.' }
});
</script>

<style scoped>
.card-wrapper {
  width: 100%;
  max-width: 500px; 
  margin-bottom: 20px;
}

.feedback-card {
  border: 1.5px solid #000;
  border-radius: 20px;
  padding: 20px;
  background-color: #fff;
  font-family: sans-serif; 
}

.card-header {
  display: flex;
  gap: 12px;
  margin-bottom: 15px;
}

.professor-badge {
  border: 1.2px solid #000;
  border-radius: 15px;
  padding: 8px 15px;
  flex-grow: 1;
  font-weight: 600;
  font-size: 0.95rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.status-badge {
  border: 1.2px solid #000;
  border-radius: 15px;
  padding: 8px 15px;
  font-size: 0.9rem;
  text-align: center;
  min-width: 80px;
}

.feedback-text {
  font-size: 0.9rem;
  line-height: 1.5;
  margin: 0;
  color: #333;
}

.more-container {
  display: flex;
  justify-content: flex-end;
  margin-top: 5px;
}

.more-btn {
  background: none;
  border: none;
  font-size: 0.85rem;
  cursor: pointer;
  color: #666;
  padding: 5px;
  border-radius: 50%;
}

.more-btn:hover {
  background-color: #ffe4d1; 
}
</style>