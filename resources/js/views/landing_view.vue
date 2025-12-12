<template>
  <div class="landing-page">
    <div class="hero-container">
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
      <div class="seal-wrapper">
        <img
          src="/assets/PUP_logo.png"
          alt="PUP Logo"
          class="seal-image"
          @click="goToPortal"
          />
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

.hero-container {
  width: 100%;
  max-width: 1280px;
  cursor: pointer;
  overflow: hidden;
  box-shadow: 0 15px 40px rgba(0,0,0,0.6);
}

.hero-image-wrapper {
  position: relative;
  height: 55vh;
  min-height: 260px;
}

.hero-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Seal */
.seal-wrapper {
  position: absolute;
  left: 50%;
  bottom: -12%;
  transform: translateX(-50%);
}

.seal-image {
  width: 220px;
  border-radius: 50%;
  box-shadow: 0 15px 40px rgba(0,0,0,0.65);
}

/* Bottom maroon band */
.hero-bottom {
  background-color: #7a0000;
  padding: 4.5rem 1rem 3rem;
  display: flex;
  justify-content: center;
}

.hero-bottom-inner {
  text-align: center;
  color: #fff;
}

.landing-title {
  letter-spacing: 0.6rem;
  font-size: 1.4rem;
  font-weight: 600;
}

.landing-tagline {
  font-style: italic;
  margin-top: 0.5rem;
}

.clickable-seal {
  cursor: pointer;
}

.clickable-seal:hover {
  transform: scale(1.03);
}

@media (max-width: 768px) {
  .seal-image {
    width: 170px;
  }

  .landing-title {
    letter-spacing: 0.35rem;
    font-size: 1.05rem;
  }
}

@media (max-width: 480px) {
  .seal-image {
    width: 140px;
  }

  .landing-title {
    font-size: 0.95rem;
    letter-spacing: 0.25rem;
  }
}
</style>