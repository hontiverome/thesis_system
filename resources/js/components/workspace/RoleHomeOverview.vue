<template>
  <div class="role-home-page">
    <div class="hero-section">
      <div class="overlay"></div>
      <div class="content-container">
        <h1 class="main-title">
          WELCOME TO
          <span class="t-red">T</span>-SIS
        </h1>
        <p class="tagline">
          {{ roleTagline }}
        </p>
      </div>

      <div class="scroll-arrow" @click="scrollToDashboard">
        <IconifyIcon icon="mdi:chevron-down" width="55" height="55" />
      </div>
    </div>

    <div ref="dashboardRef" class="dashboard-section">
      <BaseCard :title="`${roleLabel} Dashboard`">
        <div class="dashboard-grid">
          <div 
            v-for="item in dashboardItems" 
            :key="item.id"
            class="dashboard-card"
            :class="item.class"
            @click="handleItemClick(item)"
          >
            <div class="card-icon">{{ item.icon }}</div>
            <h3>{{ item.title }}</h3>
            <p>{{ item.description }}</p>
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
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { Icon as IconifyIcon } from '@iconify/vue';
import BaseCard from '@/components/ui/core/BaseCard.vue';

const route = useRoute();
const router = useRouter();

// --- Navigation Logic ---
const dashboardRef = ref(null);
const showBackToTop = ref(false);

const scrollToDashboard = () => {
  dashboardRef.value?.scrollIntoView({ behavior: 'smooth' });
};

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const handleScroll = () => {
  showBackToTop.value = window.scrollY > 400;
};

onMounted(() => window.addEventListener('scroll', handleScroll));
onUnmounted(() => window.removeEventListener('scroll', handleScroll));

// --- Role Context ---
const role = computed(() => route.params.role);

const roleLabel = computed(() => {
  const names = {
    student: 'Student',
    admin: 'Administrator',
    adviser: 'Adviser',
    faculty: 'Faculty'
  };
  return names[role.value] || 'User';
});

const roleTagline = computed(() => 'your dedicated space for organized research planning, thesis development, and academic growth.');

// --- Dashboard Logic (5 items per role) ---
const dashboardItems = computed(() => {
  const archiveCard = { 
    id: 5, 
    title: 'Thesis Archive', 
    description: 'CpE Abstract Library', 
    icon: '📁', 
    class: 'archive-card', 
    action: () => router.push(`/${role.value}/archive`) 
  };

  const items = {
    student: [
      { id: 1, title: 'My Courses', description: 'Manage enrolled courses', icon: '📚', class: 'courses-card', action: () => router.push(`/${role.value}/courses`) },
      { id: 2, title: 'Submissions', description: 'Track deadlines', icon: '📝', class: 'submissions-card', action: () => {} },
      { id: 3, title: 'Feedback', description: 'Review adviser comments', icon: '💬', class: 'feedback-card', action: () => {} },
      { id: 4, title: 'Progress', description: 'Monitor thesis status', icon: '📊', class: 'progress-card', action: () => {} },
      archiveCard
    ],
    admin: [
      { id: 1, title: 'Manage Users', description: 'User management', icon: '👥', class: 'users-card', action: () => {} },
      { id: 2, title: 'Courses', description: 'Curriculum settings', icon: '📚', class: 'courses-card', action: () => router.push(`/${role.value}/courses`) },
      { id: 3, title: 'System Logs', description: 'Activity monitoring', icon: '📋', class: 'logs-card', action: () => {} },
      { id: 4, title: 'Analytics', description: 'System reports', icon: '📊', class: 'analytics-card', action: () => {} },
      archiveCard
    ],
    adviser: [
      { id: 1, title: 'My Advisees', description: 'Advisee groups', icon: '👨‍🎓', class: 'advisees-card', action: () => {} },
      { id: 2, title: 'Courses', description: 'Advising courses', icon: '📚', class: 'courses-card', action: () => router.push(`/${role.value}/courses`) },
      { id: 3, title: 'Submissions', description: 'Student work reviews', icon: '📝', class: 'submissions-card', action: () => {} },
      { id: 4, title: 'Panel Status', description: 'Review tracking', icon: '📊', class: 'status-card', action: () => {} },
      archiveCard
    ],
    faculty: [
      { id: 1, title: 'My Classes', description: 'Assigned classes', icon: '📚', class: 'classes-card', action: () => router.push(`/${role.value}/courses`) },
      { id: 2, title: 'Submissions', description: 'Review student work', icon: '📝', class: 'submissions-card', action: () => {} },
      { id: 3, title: 'Grading', description: 'Grade & feedback', icon: '✅', class: 'grading-card', action: () => {} },
      { id: 4, title: 'Materials', description: 'Class resources', icon: '📚', class: 'materials-card', action: () => {} },
      archiveCard
    ]
  };
  return items[role.value] || [];
});

const handleItemClick = (item) => {
  if (item.action) item.action();
};
</script>

<style scoped>
/* Styles Kept from Original */
.role-home-page {
  width: 100%;
  min-height: 100vh;
  background: linear-gradient(rgba(17, 22, 28, 0.68), rgba(17, 22, 28, 0.68)),
              url('/assets/aerial_pup.jpg') center/cover no-repeat;
}

.hero-section {
  width: 100%;
  height: 100vh;
  position: relative;
  background-image: url('/assets/aerial_pup.jpg');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
}

.overlay {
  position: absolute;
  inset: 0;
  background-color: rgba(17, 22, 28, 0.68);
  z-index: 1;
}

.content-container {
  z-index: 2;
  color: #ffffff;
  max-width: 1200px;
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.main-title {
  font-family: 'Sorts Mill Goudy', serif;
  font-size: 65px;
  font-style: italic;
  color: #fbfbfb;
  margin: 0 0 20px 0;
  letter-spacing: 2px;
  text-transform: uppercase;
}

.t-red { color: #800000; font-style: italic; }

.tagline {
  font-size: 16px;
  margin: 0;
  font-weight: 300;
  letter-spacing: 1px;
}

.scroll-arrow {
  position: absolute;
  bottom: 80px;
  cursor: pointer;
  animation: bounce 2s infinite;
  transition: color 0.3s;
  z-index: 5;
}

.scroll-arrow:hover { color: #800000; }

/* Dashboard Grid with 5 Columns */
.dashboard-section {
  padding: 40px;
  background: linear-gradient(rgba(17, 22, 28, 0.85), rgba(17, 22, 28, 0.85)),
              url('/assets/aerial_pup.jpg') center/cover fixed;
  min-height: 100vh;
  color: white;
}

.dashboard-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 15px;
  margin-top: 20px;
}

.dashboard-card {
  background-color: white;
  border: 2px solid #ddd;
  border-radius: 8px;
  padding: 20px 10px;
  text-align: center;
  cursor: pointer;
  transition: all 0.4s ease;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  min-height: 180px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.dashboard-card:hover {
  transform: translateY(-5px);
  border-color: #800000;
  background-color: #f5f5f5; /* Light gray effect */
}

.card-icon { font-size: 32px; margin-bottom: 10px; }

.dashboard-card h3 {
  color: #800000;
  font-size: 14px;
  margin: 10px 0;
  font-weight: bold;
  text-transform: uppercase;
}

.dashboard-card p {
  color: #666;
  font-size: 11px;
  margin: 0;
  line-height: 1.3;
}

/* Back to Top Styling */
.back-to-top {
  position: fixed;
  bottom: 30px;
  right: 30px;
  background-color: #800000;
  color: white;
  width: 45px;
  height: 45px;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  z-index: 99;
  box-shadow: 0 4px 10px rgba(0,0,0,0.3);
}

@keyframes bounce {
  0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
  40% { transform: translateY(-15px); }
  60% { transform: translateY(-7px); }
}

.fade-enter-active, .fade-leave-active { transition: opacity 0.5s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

/* Card Accent Borders */
.courses-card { border-left: 3px solid #4CAF50; }
.submissions-card { border-left: 3px solid #2196F3; }
.feedback-card { border-left: 3px solid #FF9800; }
.progress-card { border-left: 3px solid #9C27B0; }
.archive-card { border-left: 3px solid #607D8B; }
.archive-card h3 { color: #607D8B; }
.users-card { border-left: 3px solid #00BCD4; }
.logs-card { border-left: 3px solid #795548; }
.analytics-card { border-left: 3px solid #E91E63; }
.advisees-card { border-left: 3px solid #3F51B5; }
.status-card { border-left: 3px solid #607D8B; }
.classes-card { border-left: 3px solid #4CAF50; }
.grading-card { border-left: 3px solid #8BC34A; }
.materials-card { border-left: 3px solid #FFC107; }

@media (max-width: 1024px) {
  .dashboard-grid {
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  }
}
</style>