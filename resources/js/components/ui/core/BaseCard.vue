<template>
  <div class="base-card">

    <header class="card-header">
      COURSES
    </header>

    <div class="card-content">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: 'BaseCard'
}
</script>
<style scoped>

.base-card {
  background-color: #f4f6f9;
  border-radius: 8px;
  box-shadow: 0 4px 8px #f4f6f9;
  padding-bottom: 20px;

  max-width: 1200px;
  margin: 10px auto;
}

.card-header {
  color: #E48217;
  padding: 40px 0 20px 40px;
  font-family: Arial, sans-serif;
  font-size: 1.9em;
  font-weight: bold;
  letter-spacing: 2px;
  text-transform: uppercase;
}
.card-content {
    padding: 0 40px 20px 40px;
}
</style>