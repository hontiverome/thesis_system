// Simple version that won't crash your app
export const vPermission = {
  mounted(el, binding) {
    // We will add real logic later when the User Store is ready.
    // For now, this lets the app run without errors.
    if (binding.value) {
        console.log('Permission check required:', binding.value);
    }
  }
};