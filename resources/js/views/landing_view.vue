<template>
  <div class="landing-page">
    <div class="hero-container" @click="goToPortal">
      <div class="hero-image-wrapper">
        <img
        src= "/assets/campus.jpg"
        alt= "PUP Campus"
        class= "hero-image"
      />
      </div>
      
      <div class="hero-bottom">
        <div class="hero-bottom-inner">
          <h1 class="landing-title">S I N T A N G&nbsp;&nbsp;P A A R A L A N </h1>
          <p class="landing-tagline">“Mula Sa'Yo, Para sa Bayan”</p>
        </div>
      </div>    
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