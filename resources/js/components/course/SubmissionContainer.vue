<template>
  <div class="submission-container">
    <div class="content">
      <slot></slot>
    </div>
    <div v-if="$slots['extra-feature']" class="extra-feature">
      <slot name="extra-feature"></slot>
    </div>
  </div>
</template>

<script setup>
// SubmissionContainer component for organizing submission UI
</script>

<style scoped>
.submission-container {
  padding: 20px;
  background: white;
  border-radius: 8px;
}

.content {
  margin-bottom: 20px;
}

.extra-feature {
  border-top: 1px solid #e0e0e0;
  padding-top: 20px;
}
</style>
