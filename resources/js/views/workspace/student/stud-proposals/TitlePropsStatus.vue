<template>
  <div class="table-container">
    <div class="status-table">
      <div class="table-header">
        <div class="col-title">Title</div>
        <div class="col-status">Status</div>
        <div class="col-approval">Approval Rate</div>
      </div>


      <div class="info-row">
        <div class="col-title">Submitted</div>
        <div class="col-status">Accepted/Rejected</div>
        <div class="col-approval">%</div>
      </div>


      <div
        v-for="(item, index) in tableData"
        :key="index"
        class="table-row"
      >
        <div class="col-title row-text bold-text">{{ item.title }}</div>
        <div class="col-status row-text">{{ item.status }}</div>
        <div class="col-approval row-text">{{ item.rate }}</div>
      </div>
    </div>
  </div>
</template>


<script>
export default {
  name: 'StatusTable',
  data() {
    return {
      tableData: [
        {
          title: 'Proposal Title 1',
          status: 'Submitted',
          rate: '50%'
        }
      ]
    }
  }
}
</script>


<style scoped>
.table-container {
  width: 100%;
  /* Shadow matching the image */
  filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.15));
}


.status-table {
  width: 100%;
  display: flex;
  flex-direction: column;
  background-color: white;
  border-radius: 12px;
  overflow: hidden;
}


/* --- COLUMN WIDTHS (60% - 20% - 20%) --- */
.col-title {
  flex: 6;
  padding-left: 40px;
}


.col-status {
  flex: 2;
  text-align: left;
}


.col-approval {
  flex: 2;
  text-align: center;
  padding-right: 20px;
}


/* --- HEADER --- */
.table-header {
  background-color: #800000; /* Maroon */
  color: white;
  display: flex;
  padding: 18px 0;
  font-weight: 600;
  font-size: 1.1rem;
}


/* --- SUB-HEADER INFO ROW --- */
.info-row {
  background-color: #FFFFFF;
  display: flex;
  padding: 12px 0;
  color: #555;
  font-weight: 500;
  font-size: 0.95rem;
  border-bottom: 1px solid #F0F0F0;
}


/* --- DATA ROW --- */
.table-row {
  display: flex;
  padding: 20px 0;
  align-items: center;
  background-color: #F9FAFB; /* Light gray row background from image */
}


.row-text {
  color: #333;
  font-size: 1rem;
}


.bold-text {
  font-weight: 700;
}


/* Remove table container margins if needed to fit parent */
@media (max-width: 768px) {
  .col-title { padding-left: 20px; }
}
</style>

