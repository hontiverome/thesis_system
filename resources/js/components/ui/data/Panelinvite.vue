<template>
  <div class="panel-invitation-card">
    
    <h3 class="card-title">Panel Invitation</h3>
    
    <ul class="invitation-list">
      <li 
        v-for="(item, index) in invitations" 
        :key="index" 
        class="invitation-item"
      >
        
        <div class="left-section">
          <svg 
            xmlns="http://www.w3.org/2000/svg" 
            viewBox="0 0 24 24" 
            class="star-icon"
            :class="item.status"
          >
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.007z" clip-rule="evenodd" />
          </svg>
          
          <span class="professor-name">{{ item.name }}</span>
        </div>
        
        <div class="right-section">
          <span class="status-text">
            {{ item.status.charAt(0).toUpperCase() + item.status.slice(1) }}
          </span>
        </div>
        
      </li>
    </ul>
    
    <div class="card-footer">
      <a href="#" class="more-link" @click.prevent="$emit('show-more')">
        ....more
      </a>
    </div>
    
  </div>
</template>

<script>
export default {
  name: 'PanelInvitationCard',
  props: {
    invitations: {
      type: Array,
      // UPDATED Default data to match new statuses
      default: () => [
        { name: 'Professor 1', status: 'accepted' },
        { name: 'Professor 2', status: 'rejected' },
        { name: 'Professor 3', status: 'pending' },
        { name: 'Professor 4', status: 'accepted' }
      ]
    }
  }
}
</script>

<style scoped>
/* Container Styles (Same as before) */
.panel-invitation-card {
  background-color: #FFFFFF;
  border-radius: 12px;
  padding: 20px;
  max-width: 400px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  font-family: 'Arial', sans-serif;
}

.card-title {
  font-size: 1.2rem;
  font-weight: 700;
  color: #000000;
  margin: 0 0 20px 0;
}

.invitation-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.invitation-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 0;
  border-bottom: 1px solid #F0F0F0; 
}

.left-section {
  display: flex;
  align-items: center;
  gap: 10px;
}

.professor-name {
  font-size: 0.95rem;
  color: #333333;
}

.status-text {
  font-size: 0.85rem;
  color: #757575; /* Status text remains grey */
}

/* --- NEW STAR STYLES --- */

/* Base Star Style (Default / Pending state) */
.star-icon {
  width: 18px;
  height: 18px;
  /* White fill */
  fill: #FFFFFF; 
  /* Grey stroke so it's visible against white background */
  stroke: #9CA3AF; 
  stroke-width: 1.5px;
  transition: all 0.2s ease; /* Smooth color change */
}

/* Accepted State - Green fill and stroke */
.star-icon.accepted {
  fill: #2E7D32; 
  stroke: #2E7D32; 
}

/* Rejected State - Red fill and stroke */
.star-icon.rejected {
  fill: #C62828; 
  stroke: #C62828; 
}
/* Explicit pending class (optional, as base style covers it) */
.star-icon.pending {
  fill: #FFFFFF;
  stroke: #9CA3AF;
}


/* Footer Styles (Same as before) */
.card-footer {
  display: flex;
  justify-content: flex-end;
  margin-top: 15px;
}

.more-link {
  text-decoration: none;
  color: #757575; 
  font-size: 0.9rem;
  cursor: pointer;
}

.more-link:hover {
  color: #333333;
}
</style>