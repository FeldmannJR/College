<template>
  <div class="indicatorFixed">
    <h2>{{indicator.name}}</h2>
    <h1>{{indicator.value}}</h1>
  </div>
</template>
<script>
export default {
  props: ["indicator"]
};
</script>
