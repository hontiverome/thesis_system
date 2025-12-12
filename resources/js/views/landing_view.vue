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
      <div class="seal-wrapper">
        <img
          src="/assets/PUP_logo.png"
          alt="PUP Logo"
          class="seal-image"
          @click="goToPortal"
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

.landing-page{
  width: 100%;
  height: 100vh;
  margin: 0;
  padding: 0;
}

.hero-container {
  position:relative;
  width: 100%;
  height: 100vh;
  max-width: none;
  cursor: default;
  overflow: hidden;
}

.hero-image-wrapper {
  position: absolute;
  inset:0
}

.hero-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display:block
}

/* Seal */
.seal-wrapper {
  position: absolute;
  left: 50%;
  bottom: 30vh;
  transform: translateX(-50%);
  z-index: 6;
  pointer-events:none
}

.seal-image {
  width: 400px;
  max-width: 70vw;
  border-radius: 50%;
  opacity: 0.65;
  transition:
    opacity 0.25s ease,
    transform 0.25s ease,
    filter 0.25s ease;
  filter: drop-shadow(0 0 25px rgba(255,255,255,0.25))
          drop-shadow(0 0 60px rgba(0,0,0,0.35));
  pointer-events: auto; 
  will-change: transform, opacity;
}

/* Bottom maroon band */
.hero-bottom {
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  height: 50vh;
    background: linear-gradient(
    to bottom,
    rgba(122, 0, 0, 0) 0%,
    rgba(122, 0, 0, 0.85) 18%,
    #7a0000 45%,
    #7a0000 100%
  );
  display: flex;
  justify-content: center;
  align-items: center;
}

.hero-bottom-inner {
  text-align: center;
  color: rgba(255, 255, 255, 0.85);
  transform: translateY(55px);
}

.landing-title {
  letter-spacing: 0.6rem;
  font-size: 1.4rem;
  font-weight: 600;
  text-transform: uppercase;
  text-shadow: 0 2px 10px rgba(0,0,0,0.35);
  margin: 0;
}

.landing-tagline {
  font-style: italic;
  margin-top: 0.75rem;
   text-shadow: 0 2px 10px rgba(0,0,0,0.35);
}

.clickable-seal {
  cursor: pointer;
  transition: transform 0.2s ease, opacity 0.2s ease;
}

.clickable-seal:hover {
  transform: scale(1.03);
  opacity:0.45;
}

.seal-image:active,
.seal-image:focus-visible {
  opacity: 1;
  transform: scale(1.05);
  filter:
    drop-shadow(0 0 28px rgba(255,255,255,0.55))
    drop-shadow(0 0 70px rgba(0,0,0,0.6));
}

:global(.main-content) {
  padding: 0 !important;
  margin: 0 !important;
}

:global(.content-wrapper) {
  padding: 0 !important;
  max-width: 100% !important;
}

@media (hover: hover) and (pointer: fine) {
  .seal-image:hover {
    opacity: 1;              /* full visibility */
    transform: scale(1.08);  /* slightly bigger */
    filter:
      drop-shadow(0 0 28px rgba(255,255,255,0.55))
      drop-shadow(0 0 70px rgba(0,0,0,0.6));
  }
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

  /* For mobile view (STC)*/
  @media (max-width: 480px) {
  .seal-wrapper {
    bottom: 25vh;
  }

  .seal-image {
    width: 200px;
  }

  .landing-title {
    letter-spacing: 0.25rem;
    font-size: 0.95rem;
  }

  .landing-tagline {
    font-size: 0.85rem;
  }
}

}
</style>