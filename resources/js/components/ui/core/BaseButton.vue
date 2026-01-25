<template>
  <button 
    :class="['base-button', ButtonColor, ButtonSize, ButtonState, { 'is-loading': isLoading }]"
    :style="{ width: ButtonWidth === 'full' ? '100%' : ButtonWidth }"
    :disabled="ButtonState === 'Disable' || isLoading"
    @click="$emit('click')"
  >
    <span v-if="isLoading" class="spinner"></span>

    <span v-if="!isLoading && ButtonStyle !== 'HideIcon'" class="icon-slot">
      <slot name="icon">
        <span>★</span>
      </slot>
    </span>

    <span v-if="ButtonStyle !== 'IconOnly'" class="label">
      {{ ButtonName }}
    </span>

    <span v-if="showStatusDot" class="status-dot"></span>
  </button>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  ButtonName: { type: String, default: 'Button' },
  ButtonColor: { type: String, default: 'Yellow' },   // Yellow, Maroon, Green, Red
  ButtonSize: { type: String, default: 'md' },       // sm, md, lg
  ButtonState: { type: String, default: 'Default' }, // Default, Hover, Active, Disable
  ButtonStyle: { type: String, default: 'ShowIcon' },// ShowIcon, HideIcon, IconOnly
  ButtonWidth: { type: String, default: 'auto' },    // auto, full, or px value
  isLoading: { type: Boolean, default: false }       // New Loading Prop
});

defineEmits(['click']);

const showStatusDot = computed(() => {
  return props.ButtonName === 'UPDATE' && props.ButtonColor === 'Yellow' && !props.isLoading;
});
</script>

<style scoped>
.base-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  border: none;
  border-radius: 20px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  position: relative;
}

/* Color Variants */
.Yellow { background-color: #FFC107; color: #000; }
.Maroon { background-color: #800000; color: #fff; }
.Green  { background-color: #28a745; color: #fff; }
.Red    { background-color: #dc3545; color: #fff; }

/* Sizes */
.sm { padding: 4px 12px; font-size: 12px; }
.md { padding: 10px 24px; font-size: 14px; }
.lg { padding: 14px 32px; font-size: 18px; }

/* States */
.base-button:hover { filter: brightness(90%); }
.base-button:active { transform: scale(0.98); }
.Disable, .is-loading { opacity: 0.6; cursor: not-allowed; pointer-events: none; }

/* Loading Spinner Animation */

.spinner {
  width: 16px;
  height: 16px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top-color: currentColor;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.status-dot {
  position: absolute;
  top: 6px;
  right: 8px;
  width: 6px;
  height: 6px;
  background-color: #ff4d4d;
  border-radius: 50%;
  border: 1px solid white;
}
</style>