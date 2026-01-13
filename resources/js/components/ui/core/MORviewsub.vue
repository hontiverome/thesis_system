<template>
  <div class="submissions-view">
    <table class="submission-table">
      <thead>
        <tr>
          <th>GROUP CODE</th>
          <th>RESEARCH TITLES</th>
          <th>STATUS</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="title in researchTitles" :key="title">
          <td>G1</td>
          <td>{{ title }}</td>
          <td>
            <button class="btn-accept">Accept</button>
            <button class="btn-reject">Reject</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const researchTitles = ref(['Impact of AI', 'Blockchain in Voting', 'IoT Security']);
</script>