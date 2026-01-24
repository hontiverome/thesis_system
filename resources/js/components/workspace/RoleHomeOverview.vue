<template>
  <div class="role-home-page">
    <div class="hero-section">
      <div class="overlay"></div>
      <div class="content-container">
        <h1 class="main-title">WELCOME TO <span class="t-red">T</span>-SIS</h1>
        <p class="tagline">
          your dedicated space for organized research planning, thesis development, and academic growth.
        </p>
      </div>
      
      <div class="scroll-arrow" @click="scrollToDashboard">
        <IconifyIcon icon="mdi:chevron-down" width="55" height="55" />
      </div>
    </div>

    <div ref="dashboardRef" class="dashboard-section">
      <BaseCard :title="`${roleLabel} Dashboard` || 'Dashboard'">
        <div class="dashboard-grid">
          <div 
            v-for="item in fullDashboardItems" 
            :key="item.id"
            class="dashboard-card"
            :style="{ borderLeft: `4px solid ${item.color}` }"
            @click="router.push(item.path)"
          >
            <div class="card-icon">{{ item.icon }}</div>
            <h3>{{ item.title }}</h3>
            <p>{{ item.desc }}</p>
          </div>
        </div>
      </BaseCard>
    </div>

    <Transition name="fade">
      <div v-if="showBackToTop" class="back-to-top" @click="scrollToTop">
        <IconifyIcon icon="mdi:arrow-up" width="30" height="30" />
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { Icon as IconifyIcon } from '@iconify/vue';
import BaseCard from '@/components/ui/core/BaseCard.vue';
import { useWorkspaceApi } from '@/composables/useWorkspaceApi';
import { ROLE_METADATA, COMMON_ITEMS } from '@/config/roleConfig';

const router = useRouter();
const { role, roleLabel } = useWorkspaceApi();
const dashboardRef = ref(null);
const showBackToTop = ref(false);

const currentRoleConfig = computed(() => ROLE_METADATA[role.value] || ROLE_METADATA.student);

const fullDashboardItems = computed(() => {
  const items = [...currentRoleConfig.value.dashboard];
  items.push(COMMON_ITEMS(role.value));
  return items;
});

const scrollToDashboard = () => dashboardRef.value?.scrollIntoView({ behavior: 'smooth' });
const scrollToTop = () => window.scrollTo({ top: 0, behavior: 'smooth' });
const handleScroll = () => { showBackToTop.value = window.scrollY > 400; };

onMounted(() => window.addEventListener('scroll', handleScroll));
onUnmounted(() => window.removeEventListener('scroll', handleScroll));
</script>

<style scoped>
.role-home-page {
  width: 100%; min-height: 100vh;
  /* Fixed background image with 0.35 brightness overlay */
  background: linear-gradient(rgba(17, 22, 28, 0.35), rgba(17, 22, 28, 0.35)),
              url('/assets/aerial_pup.jpg') center/cover no-repeat fixed;
}
.hero-section { height: 100vh; position: relative; display: flex; justify-content: center; align-items: center; text-align: center; }
.overlay { position: absolute; inset: 0; background-color: rgba(17, 22, 28, 0.35); z-index: 1; }
.content-container { z-index: 2; color: #ffffff; text-shadow: 0 2px 10px rgba(0, 0, 0, 0.6); }
.main-title { font-family: 'Sorts Mill Goudy', serif; font-size: 65px; font-style: italic; color: #ffffff; letter-spacing: 2px; }
.t-red { color: #800000; }
.tagline { font-size: 18px; font-weight: 300; max-width: 800px; margin: 0 auto; line-height: 1.5; color: #ffffff; }

.scroll-arrow {
  position: absolute;
  bottom: 90px; /* Moved up slightly */
  left: 50%;
  transform: translateX(-50%);
  z-index: 10;
  cursor: pointer;
  color: #ffffff;
  filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));
  animation: bounce 2s infinite;
}

.dashboard-section { padding: 40px; min-height: 100vh; background: transparent; }
.dashboard-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px; }
.dashboard-card { background: white; border-radius: 8px; padding: 20px; text-align: center; cursor: pointer; transition: 0.3s; min-height: 180px; display: flex; flex-direction: column; justify-content: center; }
.dashboard-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.15); }

.back-to-top {
  position: fixed; bottom: 30px; right: 30px; background-color: #800000;
  color: white; width: 50px; height: 50px; border-radius: 50%;
  display: flex; justify-content: center; align-items: center; cursor: pointer; z-index: 100;
}

@keyframes bounce {
  0%, 20%, 50%, 80%, 100% { transform: translate(-50%, 0); }
  40% { transform: translate(-50%, -15px); }
  60% { transform: translate(-50%, -7px); }
}
.fade-enter-active, .fade-leave-active { transition: opacity 0.5s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>