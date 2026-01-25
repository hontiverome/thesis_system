<template>
  <BaseCourseOverview subTitle="Methods of Research">
    
    <BaseCard title="GROUP # SECTION">
      
      <template #header-right>
        <span class="adviser-label">ASSIGNED ADVISER</span>
      </template>

      <div class="proposals-wrapper">
        <div class="proposals-header">
          <h3 class="title-label">TITLE PROPOSALS</h3>
          
          <BaseButton 
            ButtonName="Add Title" 
            ButtonColor="Yellow" 
            ButtonSize="sm"
          >
            <template #icon>+</template>
          </BaseButton>
        </div>

        <StudentProposals />
      </div>
    </BaseCard>

  </BaseCourseOverview>
</template>

<script setup>
import BaseCourseOverview from '@/components/ui/core/BaseCourseOverview.vue';
import BaseCard from '@/components/ui/core/BaseCard.vue';
import BaseButton from '@/components/ui/core/BaseButton.vue';
import StudentProposals from '@/views/workspace/student/stud-proposals/TitlePropsStatus.vue';
</script>

<style scoped>
.adviser-label {
  font-weight: bold;
  /* Matches the dark red text in your screenshot */
  color: #800000; 
}

.proposals-wrapper {
  margin-top: 10px;
}

.proposals-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.title-label {
  font-weight: 800;
  letter-spacing: 1.5px;
  color: #333;
}
</style>