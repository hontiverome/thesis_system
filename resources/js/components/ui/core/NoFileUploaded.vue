<template>
  <div class="empty-state-container">
    <div class="content-box">
      <div class="icon-container">
        <slot name="icon">
          <svg
            viewBox="0 0 24 24"
            width="64"
            height="64"
            fill="none"
            stroke="currentColor"
            stroke-width="1.5"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <path d="M12 18v-6m-3 3 3-3 3 3"></path>
          </svg>
        </slot>
      </div>

      <h2 class="title">{{ title }}</h2>
      <p class="subtitle">{{ subtitle }}</p>

      <div v-if="$slots.action" class="action-wrapper">
        <slot name="action"></slot>
      </div>
    </div>
  </div>
</template>

<script setup>
/**
 * PROPS:
 * Allows the parent to control the message. 
 * Defaults to the "Waiting for Upload" state.
 */
defineProps({
  title: {
    type: String,
    default: 'Your group has not yet uploaded the file.'
  },
  subtitle: {
    type: String,
    default: 'PDF format only.'
  }
});
</script>

<style scoped>
.empty-state-container {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 400px; /* Standard empty state height */
  padding: 2rem;
  background-color: #ffffff;
  /* If you want it to match your feedback card border: */
  /* border: 1.5px solid #000; */
  /* border-radius: 20px; */
}

.content-box {
  text-align: center;
  max-width: 500px;
}

.icon-container {
  color: #1a1a1b;
  margin-bottom: 1.5rem;
  display: flex;
  justify-content: center;
  /* Animation to make it feel less static */
  animation: float 3s ease-in-out infinite;
}

@keyframes float {
  0% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
  100% { transform: translateY(0px); }
}

.title {
  font-family: 'Courier New', Courier, monospace; /* Matching your Feedback Card */
  font-size: 1.5rem;
  font-weight: 700;
  color: #000000;
  margin: 0 0 0.75rem 0;
  line-height: 1.3;
}

.subtitle {
  font-family: sans-serif;
  font-size: 1rem;
  color: #757575;
  margin: 0;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.action-wrapper {
  margin-top: 2rem;
}
</style>