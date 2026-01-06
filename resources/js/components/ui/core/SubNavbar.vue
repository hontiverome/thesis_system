<template>
  <nav class="sub-nav">
    <ul class="nav-list">
      <li 
        v-for="item in navItems" 
        :key="item"
        class="nav-item"
        :class="{ active: activeTab === item }"
        @click="selectTab(item)"
      >
        {{ item }}
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  name: 'SubNavbar',
  data() {
    return {
      activeTab: 'SUBMISSION', // Default active tab
      navItems: ['SUBMISSION', 'GRADES', 'FEEDBACK', 'PANEL STATUS']
    }
  },
  methods: {
    selectTab(item) {
      this.activeTab = item;
      // Optional: Emit event to parent if needed
      // this.$emit('change', item);
    }
  }
}
</script>

<style scoped>
.sub-nav {
  background-color: #008BA3; 
  width: 100%;
  padding: 12px 0;
  display: flex;
  
  /* MOVED TO LEFT */
  justify-content: flex-start; 
  padding-left: 40px; /* Adjust this to move the whole list further right/left */

  position: relative;
  z-index: 10;
}

.nav-list {
  display: flex;
  
  /* MOVED TO LEFT */
  justify-content: flex-start;
  
  /* Spacing between the words */
  gap: 200px; 
  
  width: auto; /* Changed from 100% to auto so it doesn't stretch */
  list-style: none;
  margin: 0;
  padding: 0;
}

.nav-item {
  color: #FFFFFF;
  font-family: 'Source Sans Pro', Arial, sans-serif;
  font-weight: bold;
  font-size: 0.9rem;
  letter-spacing: 1.5px;
  cursor: pointer;
  text-transform: uppercase;
  transition: color 0.2s ease;
}

/* Active State (Red) */
.nav-item.active {
  color: #800000; 
}

.nav-item:hover {
  opacity: 0.8;
}
</style>