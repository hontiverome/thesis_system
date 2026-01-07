<template>
  <div class="modal-overlay">
    
    <div class="panel-invitation-popup">
      
      <button class="close-btn" @click="$emit('close')">×</button>
      
      <h2 class="popup-title">Panel Invitation</h2>
      
      <div class="list-header">
        <span class="header-item">PROFESSOR</span>
        <span class="header-item">DATE INVITED</span>
        <span class="header-item">STATUS</span>
      </div>
      
      <ul class="invitation-list">
        
        <li 
          v-for="(item, index) in invitations" 
          :key="index" 
          class="invitation-item"
        >
          
          <div class="professor-info">
            <svg 
              xmlns="http://www.w3.org/2000/svg" 
              viewBox="0 0 24 24" 
              fill="currentColor" 
              class="star-icon"
              :class="item.status.toLowerCase()"
            >
              <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.007z" clip-rule="evenodd" />
            </svg>
            <span class="professor-name">{{ item.name }}</span>
          </div>
          
          <div class="date-invited">
            {{ item.date }}
          </div>
          
          <div class="status-text">
            {{ item.status }}
          </div>
          
        </li>
      </ul>
      
    </div>
  </div>
</template>

<script>
export default {
  name: 'PanelInvitationPopup',
  props: {
    invitations: {
      type: Array,
      // UPDATED: Added a 'date' field to the default data
      default: () => [
        { 
          name: 'Professor 1 (Part time)', 
          date: 'Oct 24, 2025', 
          status: 'Approved' 
        }
      ]
    }
  }
}
</script>

<style scoped>
/* --- Modal Overlay --- */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5); 
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

/* --- Popup Box --- */
.panel-invitation-popup {
  background-color: #FFFFFF;
  border-radius: 20px;
  padding: 30px;
  width: 700px; /* Made slightly wider to fit dates nicely */
  max-width: 90%;
  box-shadow: 0 10px 25px rgba(0,0,0,0.2);
  font-family: 'Arial', sans-serif;
  position: relative;
}

/* --- Close Button --- */
.close-btn {
  position: absolute;
  top: -15px;
  right: -15px;
  width: 35px;
  height: 35px;
  background-color: #FFC1C1;
  color: #FFFFFF;
  border: none;
  border-radius: 50%;
  font-size: 1.2rem;
  font-weight: bold;
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;
  box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.close-btn:hover { background-color: #ff9e9e; }

/* --- Typography --- */
.popup-title {
  font-size: 2rem;
  font-weight: 800;
  text-align: center;
  margin-bottom: 30px;
  color: #000;
  letter-spacing: 1px;
}

/* --- Headers --- */
.list-header {
  display: flex;
  justify-content: space-between;
  padding: 0 20px 10px;
  font-weight: 700;
  color: #000;
  font-size: 0.95rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Column alignment */
.header-item { flex: 1; text-align: center; }
.header-item:first-child { flex: 2; text-align: left; } 
.header-item:last-child { text-align: right; }

/* --- List Items --- */
.invitation-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.invitation-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 20px;
  border-radius: 5px;
}

.invitation-item:nth-child(odd) { background-color: #F9FAFB; }
.invitation-item:nth-child(even) { background-color: #FFFFFF; }

.professor-info {
  flex: 2;
  display: flex;
  align-items: center;
  gap: 12px;
}

/* Date Column Style */
.date-invited { 
  flex: 1; 
  text-align: center; 
  color: #555; /* Slightly lighter black */
  font-size: 0.95rem;
}

.status-text {
  flex: 1;
  text-align: right;
  color: #757575; 
  font-size: 0.95rem;
}

.professor-name {
  font-size: 1rem;
  color: #333;
}

/* --- Star Colors --- */
.star-icon { width: 20px; height: 20px; }
.star-icon.approved { color: #2E7D32; }
.star-icon.declined { color: #C62828; }
.star-icon.canceled { color: #C62828; }
</style>