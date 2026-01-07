<template>
  <button class="add-button" @click="$emit('click')">
    <span class="plus-sign">+</span>
    <span class="button-label">{{ label }}</span>
  </button>
</template>

<script>
export default {
  name: 'AddButton',
  props: {
    // Allows you to change the text.
    // Defaults to 'Add Title' as seen in the image.
    label: {
      type: String,
      default: 'Add Title'
    }
  }
  // The button automatically emits a 'click' event when pressed.
}
</script>

<style scoped>
.add-button {
  /* --- Style matching the image --- */
  background-color: #E5E7EB; /* Light Gray background */
  color: #1F2937;            /* Dark gray/black text */
  border: none;              /* No border */
  border-radius: 50px;       /* Pill shape */
  padding: 10px 24px;        /* Spacing inside the button */
  
  /* --- Typography & Layout --- */
  font-family: 'Arial', sans-serif;
  font-size: 1rem;
  font-weight: 500;          /* Medium weight */
  cursor: pointer;           /* Hand icon on hover */
  display: inline-flex;      /* Keeps text and icon aligned */
  align-items: center;       /* Centers vertically */
  gap: 8px;                  /* Space between the '+' and the text */
  
  /* --- Polish --- */
  transition: background-color 0.2s ease, box-shadow 0.2s ease;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05); /* Subtle depth */
}

/* Hover Effect */
.add-button:hover {
  background-color: #D1D5DB; /* Slightly darker gray on hover */
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.plus-sign {
  font-size: 1.2rem; /* Makes the '+' slightly larger */
  line-height: 1;    /* Ensures it doesn't mess up vertical alignment */
}
</style>