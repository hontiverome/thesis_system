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
    </div>

    <div class="dashboard-section">
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
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import BaseCard from '@/components/ui/core/BaseCard.vue';

const route = useRoute();
const router = useRouter();

// Extract role from URL path
const role = computed(() => route.params.role);

const roleName = computed(() => {
  const names = {
    student: 'Student',
    admin: 'Administrator',
    adviser: 'Adviser',
    faculty: 'Faculty'
  };
  return names[role.value] || 'User';
});

const roleLabel = computed(() => roleName.value);

const roleTagline = computed(() => {
  return 'your dedicated space for organized research planning, thesis development, and academic growth.';
});

const dashboardItems = computed(() => {
  const items = {
    student: [
      {
        id: 1,
        title: 'My Courses',
        description: 'View and manage your enrolled courses',
        icon: '📚',
        class: 'courses-card',
        action: () => router.push(`/${role.value}/courses`)
      },
      {
        id: 2,
        title: 'Submissions',
        description: 'Track your course submissions and deadlines',
        icon: '📝',
        class: 'submissions-card',
        action: () => {}
      },
      {
        id: 3,
        title: 'Feedback',
        description: 'Review feedback from your adviser',
        icon: '💬',
        class: 'feedback-card',
        action: () => {}
      },
      {
        id: 4,
        title: 'Progress',
        description: 'Monitor your thesis progress',
        icon: '📊',
        class: 'progress-card',
        action: () => {}
      }
    ],
    admin: [
      {
        id: 1,
        title: 'Manage Users',
        description: 'Add, edit, or remove system users',
        icon: '👥',
        class: 'users-card',
        action: () => {}
      },
      {
        id: 2,
        title: 'Courses',
        description: 'Manage curriculum and courses',
        icon: '📚',
        class: 'courses-card',
        action: () => router.push(`/${role.value}/courses`)
      },
      {
        id: 3,
        title: 'System Logs',
        description: 'View system activity and logs',
        icon: '📋',
        class: 'logs-card',
        action: () => {}
      },
      {
        id: 4,
        title: 'Analytics',
        description: 'View system analytics and reports',
        icon: '📊',
        class: 'analytics-card',
        action: () => {}
      }
    ],
    adviser: [
      {
        id: 1,
        title: 'My Advisees',
        description: 'View and manage your advisee groups',
        icon: '👨‍🎓',
        class: 'advisees-card',
        action: () => {}
      },
      {
        id: 2,
        title: 'Courses',
        description: 'View your advising courses',
        icon: '📚',
        class: 'courses-card',
        action: () => router.push(`/${role.value}/courses`)
      },
      {
        id: 3,
        title: 'Submissions',
        description: 'Review student submissions',
        icon: '📝',
        class: 'submissions-card',
        action: () => {}
      },
      {
        id: 4,
        title: 'Panel Status',
        description: 'Track panel review status',
        icon: '📊',
        class: 'status-card',
        action: () => {}
      }
    ],
    faculty: [
      {
        id: 1,
        title: 'My Classes',
        description: 'View your assigned classes',
        icon: '📚',
        class: 'classes-card',
        action: () => router.push(`/${role.value}/courses`)
      },
      {
        id: 2,
        title: 'Submissions',
        description: 'Review student submissions',
        icon: '📝',
        class: 'submissions-card',
        action: () => {}
      },
      {
        id: 3,
        title: 'Grading',
        description: 'Grade submissions and provide feedback',
        icon: '✅',
        class: 'grading-card',
        action: () => {}
      },
      {
        id: 4,
        title: 'Class Materials',
        description: 'Manage course materials and resources',
        icon: '📚',
        class: 'materials-card',
        action: () => {}
      }
    ]
  };
  return items[role.value] || [];
});

const handleItemClick = (item) => {
  if (item.action) {
    item.action();
  }
};
</script>

<style scoped>
.role-home-page {
  width: 100%;
  min-height: 100vh;
  background: linear-gradient(rgba(17, 22, 28, 0.68), rgba(17, 22, 28, 0.68)),
              url('/assets/aerial_pup.jpg') center/cover no-repeat;
}

.hero-section {
  width: 100%;
  min-height: 100vh;
  position: relative;
  background-image: url('/assets/aerial_pup.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(17, 22, 28, 0.68);
  z-index: 1;
}

.content-container {
  z-index: 2;
  color: #ffffff;
  max-width: 1200px;
  padding: 20px;
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

.t-red {
  color: #800000;
  font-style: italic;
}

.tagline {
  font-size: 16px;
  margin: 0;
  font-weight: 300;
  letter-spacing: 1px;
}

.dashboard-section {
  padding: 50px;
  background: linear-gradient(rgba(17, 22, 28, 0.85), rgba(17, 22, 28, 0.85)),
              url('/assets/aerial_pup.jpg') center/cover fixed;
  min-height: 100vh;
  color: white;
}

.dashboard-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 30px;
  margin-top: 20px;
}

.dashboard-card {
  background-color: white;
  border: 2px solid #ddd;
  border-radius: 8px;
  padding: 30px;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.dashboard-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
  border-color: #800000;
}

.card-icon {
  font-size: 48px;
  margin-bottom: 15px;
}

.dashboard-card h3 {
  color: #800000;
  font-size: 18px;
  margin: 15px 0;
  font-weight: bold;
  text-transform: uppercase;
}

.dashboard-card p {
  color: #666;
  font-size: 14px;
  margin: 0;
  line-height: 1.5;
}

/* Card-specific colors */
.courses-card {
  border-left: 4px solid #4CAF50;
}

.courses-card h3 {
  color: #4CAF50;
}

.submissions-card {
  border-left: 4px solid #2196F3;
}

.submissions-card h3 {
  color: #2196F3;
}

.feedback-card {
  border-left: 4px solid #FF9800;
}

.feedback-card h3 {
  color: #FF9800;
}

.progress-card {
  border-left: 4px solid #9C27B0;
}

.progress-card h3 {
  color: #9C27B0;
}

.users-card {
  border-left: 4px solid #00BCD4;
}

.users-card h3 {
  color: #00BCD4;
}

.logs-card {
  border-left: 4px solid #795548;
}

.logs-card h3 {
  color: #795548;
}

.analytics-card {
  border-left: 4px solid #E91E63;
}

.analytics-card h3 {
  color: #E91E63;
}

.advisees-card {
  border-left: 4px solid #3F51B5;
}

.advisees-card h3 {
  color: #3F51B5;
}

.status-card {
  border-left: 4px solid #607D8B;
}

.status-card h3 {
  color: #607D8B;
}

.classes-card {
  border-left: 4px solid #4CAF50;
}

.classes-card h3 {
  color: #4CAF50;
}

.grading-card {
  border-left: 4px solid #8BC34A;
}

.grading-card h3 {
  color: #8BC34A;
}

.materials-card {
  border-left: 4px solid #FFC107;
}

.materials-card h3 {
  color: #FFC107;
}
</style>
