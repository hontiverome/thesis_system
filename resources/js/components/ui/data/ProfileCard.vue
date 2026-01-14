<template>
  <div class="directory-container">
    <div class="header-actions">
      <div class="filter-slot">
        <slot name="filters"></slot>
      </div>

      <div class="icon-group">
        <Icon 
          icon="ci:hamburger-lg" 
          class="action-icon" 
          :class="{ active: viewType === 'list' }"
          @click="viewType = 'list'"
        />
        <Icon 
          icon="ci:grid-big-round" 
          class="action-icon" 
          :class="{ active: viewType === 'grid' }"
          @click="viewType = 'grid'"
        />
      </div>
    </div>

    <div v-if="loading" :class="['layout-container', viewType === 'grid' ? 'grid-layout' : 'list-layout']">
      <div v-for="n in 6" :key="n" class="card skeleton-card">
        <div class="skeleton-avatar"></div>
        <div class="skeleton-details">
          <div class="skeleton-line title"></div>
          <div class="skeleton-line text"></div>
          <div class="skeleton-line text short"></div>
        </div>
      </div>
    </div>

    <div v-else-if="faculty.length === 0" class="empty-state">
      <Icon icon="tabler:users-off" width="48" color="#ccc" />
      <p>No faculty members found.</p>
    </div>

    <div v-else :class="['layout-container', viewType === 'grid' ? 'grid-layout' : 'list-layout']">
      <div v-for="(member, index) in faculty" :key="member.id || index" class="card">
        <button class="info-btn" @click="$emit('view-details', member)">
          <Icon icon="fluent:info-24-regular" width="22" height="22" />
        </button>

        <div class="avatar-container">
          <img 
            :src="member.image || defaultPlaceholder" 
            :alt="member.name" 
            class="avatar"
            @error="(e) => e.target.src = defaultPlaceholder"
          />
        </div>

        <div class="details">
          <h3 class="name">{{ member.name }}</h3>
          <p class="faculty-id">{{ member.id }}</p>
          <p class="position">{{ member.position }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Icon } from '@iconify/vue';

// DEVELOPER PROPS
const props = defineProps({
  // The data fetched from the API
  faculty: {
    type: Array,
    required: true,
    default: () => []
  },
  // Toggle this true/false while the API is fetching
  loading: {
    type: Boolean,
    default: false
  }
});

// DEVELOPER EMITS
defineEmits(['view-details']);

const viewType = ref('grid');
const defaultPlaceholder = 'https://via.placeholder.com/150?text=No+Image';
</script>

<style scoped>
.directory-container {
  padding: 40px;
  background-color: #ffffff;
}

.header-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
}

.icon-group {
  display: flex;
  gap: 15px;
  align-items: center;
}

.action-icon {
  font-size: 34px;
  color: #e0e0e0;
  cursor: pointer;
  transition: color 0.3s ease;
}

.action-icon.active {
  color: #a33131;
}

/* --- Layout Containers --- */
.layout-container {
  transition: all 0.3s ease;
}

.grid-layout {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 25px;
}

.list-layout {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

/* --- Card Styling --- */
.card {
  background: #f1f1f1;
  position: relative;
  transition: transform 0.2s ease;
}

.grid-layout .card {
  border-radius: 20px;
  padding: 40px 20px;
  text-align: center;
}

.list-layout .card {
  border-radius: 15px;
  padding: 15px 30px;
  display: flex;
  align-items: center;
}

/* --- Avatar Handling --- */
.avatar-container {
  overflow: hidden;
  border-radius: 50%;
  background: #ddd;
}

.grid-layout .avatar-container {
  width: 110px;
  height: 110px;
  margin: 0 auto 15px;
}

.list-layout .avatar-container {
  width: 60px;
  height: 60px;
  margin-right: 25px;
  flex-shrink: 0;
}

.avatar { width: 100%; height: 100%; object-fit: cover; }

/* --- Text Styles --- */
.name { margin: 0; font-size: 1.15rem; color: #333; font-weight: 600; }
.faculty-id { margin: 2px 0; font-size: 0.95rem; color: #777; }
.position { margin-top: 5px; font-size: 0.85rem; font-style: italic; color: #555; }

/* --- Skeleton Shimmer Animation --- */
.skeleton-card { background: #f9f9f9 !important; border: 1px solid #eee; }

.skeleton-avatar, .skeleton-line {
  background: linear-gradient(90deg, #ececec 25%, #f5f5f5 50%, #ececec 75%);
  background-size: 200% 100%;
  animation: shimmer 1.5s infinite;
}

.skeleton-avatar {
  width: 100px; height: 100px; border-radius: 50%; 
  margin: 0 auto 15px;
}

.skeleton-line { height: 12px; margin-bottom: 10px; border-radius: 4px; }
.skeleton-line.title { height: 20px; width: 60%; margin: 0 auto 15px; }
.skeleton-line.text { width: 80%; margin: 0 auto 8px; }

@keyframes shimmer {
  0% { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}

/* --- Misc --- */
.info-btn {
  position: absolute; top: 15px; right: 15px;
  background: none; border: none; cursor: pointer; color: #444;
}

.empty-state {
  text-align: center; padding: 100px 0; color: #999;
}
</style>