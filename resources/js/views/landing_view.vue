<template>
  <div class="landing-page">
    <div class="content" @click="goToPortal">
      <div class="logo-container">
        <div class="logo-placeholder">LOGO</div> 
      </div>
      
      <h1>SINTANG PAARALAN</h1>
      <p>"Mula Sa'Yo, Para sa Bayan"</p>
      
      <div class="loader"></div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

const goToPortal = () => {
  router.push('/portal');
};
</script>

<style scoped>
.landing-page {
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: var(--bg-color, #f8f9fa);
  cursor: pointer;
  text-align: center;
}

.logo-placeholder {
  width: 120px;
  height: 120px;
  background: var(--primary-color, #4F46E5);
  border-radius: 50%;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 2rem;
  font-weight: bold;
}

</style>