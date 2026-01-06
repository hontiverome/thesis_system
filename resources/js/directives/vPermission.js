import { useUserStore } from '@/stores/user';


export const vPermission = {
  // 1. Run when the element first appears
  mounted(el, binding) {
    checkPermission(el, binding);
  },


  // 2. Run again if the data changes (ROBUSTNESS)
  updated(el, binding) {
    checkPermission(el, binding);
  }
};


// Helper function to avoid repeating code
function checkPermission(el, binding) {
  const userStore = useUserStore();
  const requiredCapability = binding.value;


  if (!userStore.can(requiredCapability)) {
    // Hide it if not allowed
    el.style.display = 'none';
  } else {
    // Show it if allowed (restores it if it was previously hidden)
    el.style.display = '';
  }
}