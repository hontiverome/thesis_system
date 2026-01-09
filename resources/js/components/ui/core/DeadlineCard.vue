

For different text on the base card

<template>
  <div class="rounded-card">
   
    <div class="header-row">
      <h2 class="title-header">{{ title }}</h2>
     
      <span class="deadline-text">{{ deadline }}</span>
    </div>
   
    <div class="card-content">
      <slot></slot>
    </div>


  </div>
</template>


<script>
export default {
  name: 'RoundedCard',
  props: {
    // Allows the parent to set the title. Defaults to "TITLE PROPOSALS" if missing.
    title: {
      type: String,
      default: 'TITLE PROPOSALS'
    },
    // Allows the parent to set the date. Defaults to "TBA" if missing.
    deadline: {
      type: String,
      default: 'OPEN UNTIL: TBA'
    }
  }
}
</script>


<style scoped>
/* --- Card Container --- */
.rounded-card {
  background-color: #FFFFFF;
  border-radius: 20px;
  padding: 40px;
  max-width: 800px; /* Adjust this width if you want the card wider/narrower */
  margin: 20px auto;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); /* Soft shadow */
  font-family: 'Arial', sans-serif;
}


/* --- Header Layout --- */
.header-row {
  display: flex;
  justify-content: space-between; /* Pushes Title Left, Date Right */
  align-items: center;            /* Vertically centers them */
  margin-bottom: 30px;            /* Spacing between header and content */
  border-bottom: 1px solid transparent; /* Keeps layout stable */
}


/* --- Typography --- */
.title-header {
  font-size: 1.5rem;      
  font-weight: 900;        /* Extra bold */
  color: #000000;          
  text-transform: uppercase;
  letter-spacing: 2px;    
  margin: 0;              
}


.deadline-text {
  font-size: 0.9rem;
  font-weight: bold;
  color: #D32F2F;          /* Red color for urgency */
  text-transform: uppercase;
  letter-spacing: 1px;
}


/* --- Content Wrapper (Optional, for extra control) --- */
.card-content {
  width: 100%;
}
</style>

