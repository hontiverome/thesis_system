<template>
  <div class="panel-approval-card">
    <h2 class="section-title">{{ title }}</h2>

    <div class="approval-list">
      <div 
        v-for="(item, index) in panelList" 
        :key="index" 
        class="approval-row"
      >
        <div class="pill-box name-box">
          {{ item.name || 'To Be Assigned' }}
        </div>

        <div class="pill-box status-box" :class="getStatusClass(item.status)">
          {{ item.status }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  title: { type: String, default: 'Panel Approval' },
  panelList: {
    type: Array,
    required: true,
    default: () => []
  }
});

// Helper to handle color logic
const getStatusClass = (status) => {
  if (!status) return '';
  const s = status.toLowerCase();
  if (s.includes('confirm')) return 'status-confirmed';
  if (s.includes('wait') || s.includes('pend')) return 'status-waiting';
  return '';
};
</script>

<style scoped>
/* ... your existing container and title styles ... */

.pill-box {
  border: 2px solid #000; /* Thicker borders look better with this style */
  border-radius: 50px;
  padding: 10px 25px;
  font-weight: 800;
  background-color: #fff;
}

/* Dynamic Status Colors */
.status-confirmed {
  background-color: #e2fbe8; /* Soft green */
}

.status-waiting {
  background-color: #fff9db; /* Soft yellow */
}

.name-box {
  flex-grow: 1;
}

.status-box {
  min-width: 130px;
  text-align: center;
}
</style>