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
      /* Updated to Title Case */
      activeTab: 'Submission',
      navItems: ['Submission', 'Feedback']
    }
  },
  methods: {
    selectTab(item) {
      this.activeTab = item;
    }
  }
}
</script>

<style scoped>
.sub-nav {
  background-color: #E48319; 
  width: 100%; 
  padding: 12px 0; 
  display: flex;
  justify-content: flex-start; 
  padding-left: 40px; 
  position: relative;
  z-index: 10;
  box-sizing: border-box;
}

.nav-list {
  display: flex;
  flex-direction: row; 
  justify-content: flex-start;
  gap: 80px; 
  list-style: none;
  margin: 0;
  padding: 0;
}

.nav-item {
  color: #ffffff; 
  font-family: 'Source Sans Pro', Arial, sans-serif;
  font-weight: 750; 
  font-size: 1rem; /* Slightly larger as Title Case looks smaller than Uppercase */
  letter-spacing: 1.2px;
  cursor: pointer;
  
  /* REMOVED text-transform: uppercase */
  
  transition: all 0.3s ease;
  line-height: 1; 
}

/* Active State (Dark Red) */
.nav-item.active {
  color: #720000; 
}

.nav-item:hover {
  color: #720000;
  opacity: 0.9;
}
</style>