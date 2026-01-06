<template>
  <div class="status-table">
    
    <div class="table-header">
      <div class="col-title">Title</div>
      <div class="col-status">Status</div>
      <div class="col-menu"></div> </div>

    <div 
      v-for="(item, index) in tableData" 
      :key="index" 
      class="table-row"
    >
      <div class="col-title row-text">
        {{ item.title }}
      </div>

      <div class="col-status row-text">
        {{ item.status }}
      </div>

      <div class="col-menu">
        <button class="menu-btn">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
            <circle cx="12" cy="5" r="2" fill="currentColor" />
            <circle cx="12" cy="12" r="2" fill="currentColor" />
            <circle cx="12" cy="19" r="2" fill="currentColor" />
          </svg>
        </button>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  name: 'StatusTable',
  data() {
    return {
      // You can replace this later with data from your database
      tableData: [
        { title: 'Proposal Title 1', status: 'Submitted' },
        { title: 'Proposal Title 2', status: 'Pending' },
        { title: 'Proposal Title 3', status: 'Approved' }
      ]
    }
  }
}
</script>

<style scoped>
/* --- CONTAINER --- */
.status-table {
  width: 100%;
  font-family: 'Arial', sans-serif;
}

/* --- SHARED COLUMN WIDTHS --- */
/* These classes ensure the Header and Rows align perfectly */
.col-title {
  flex: 6; /* 60% */
  text-align: left;
  padding-left: 40px; /* Matches the indentation in your design */
}

.col-status {
  flex: 3; /* 30% */
  text-align: left;
}

.col-menu {
  flex: 1; /* 10% */
  display: flex;
  justify-content: center;
  align-items: center;
}

/* --- HEADER STYLES --- */
.table-header {
  background-color: #800000; /* Dark Maroon */
  color: #FFFFFF;
  display: flex;
  width: 100%;
  padding: 15px 0;
  font-weight: bold;
  font-size: 1.1rem;
  letter-spacing: 0.5px;
}

/* --- ROW STYLES --- */
.table-row {
  background-color: #FFFFFF;
  border-bottom: 1px solid #E0E0E0; /* Grey separator line */
  display: flex;
  width: 100%;
  padding: 20px 0;
  align-items: center;
  transition: background-color 0.2s ease;
}

.table-row:hover {
  background-color: #F9FAFB; /* Subtle hover effect */
}

.row-text {
  color: #333333;
  font-weight: 500;
  font-size: 0.95rem;
}

/* --- BUTTON STYLES --- */
.menu-btn {
  background: none;
  border: none;
  cursor: pointer;
  color: #000000;
  padding: 8px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.menu-btn:hover {
  background-color: #EEEEEE;
}
</style>