<template>
  <div class="panel-approval-card">
    
    <h2 class="section-title">Panel Approval</h2>

    <div class="approval-list">
      
      <div 
        v-for="(item, index) in panelList" 
        :key="index" 
        class="approval-row"
      >
        
        <div v-if="item.name" class="pill-box name-box">
          {{ item.name }}
        </div>
        <div v-else class="spacer"></div>

        <div class="pill-box status-box">
          {{ item.status }}
        </div>

      </div>

    </div>

  </div>
</template>

<script>
export default {
  name: 'PanelApprovalCard',
  props: {
    panelList: {
      type: Array,
      // Default data matching your image
      default: () => [
        { name: 'Professor', status: 'Confirmed' },
        { name: 'Professor', status: 'Confirmed' },
        { name: 'Professor', status: 'Waiting..' },
        { name: null, status: 'Pending' } // No name for the last row
      ]
    }
  }
}
</script>

<style scoped>
/* --- Container --- */
.panel-approval-card {
  font-family: 'Courier New', Courier, monospace; /* Typewriter font */
  max-width: 500px;
  /* margin: 20px auto; (Optional: Centers it if standalone) */
}

/* --- Typography --- */
.section-title {
  font-family: 'Arial', sans-serif; /* Title looks like sans-serif bold */
  font-weight: 900;
  font-size: 1.8rem;
  margin-bottom: 25px;
  color: #000;
  text-transform: capitalize;
}

/* --- Layout --- */
.approval-list {
  display: flex;
  flex-direction: column;
  gap: 15px; /* Space between rows */
}

.approval-row {
  display: flex;
  justify-content: space-between; /* Pushes items to far left and right */
  align-items: center;
  gap: 20px; /* Gap between Name and Status */
}

/* --- Pill Box Styling --- */
.pill-box {
  border: 1.5px solid #000; /* Black border */
  border-radius: 50px;      /* Pill shape */
  padding: 10px 25px;
  font-weight: 700;
  font-size: 1rem;
  background-color: #FFFFFF;
  white-space: nowrap;      /* Prevents text from wrapping */
}

/* Specific Sizes */
.name-box {
  flex-grow: 1; /* Name box takes up remaining space */
  text-align: left;
}

.status-box {
  min-width: 120px; /* Ensures all status boxes are roughly same width */
  text-align: center;
}

/* --- Spacer for missing names --- */
.spacer {
  flex-grow: 1;
}
</style>