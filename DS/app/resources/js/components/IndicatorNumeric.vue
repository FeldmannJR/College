<template>
  <div class="indicatorNumeric" :style="{opacity: opacity}">
    <!-- Titulo do indicador -->
    <h2>{{indicator.text}}</h2>
    <!-- Valor do indicador -->
    <h1>{{indicator.value}}</h1>
  </div>
</template>
<script>
export default {
  props: ["indicator"],
  data() {
    return {
      opacity: 0
    };
  },
  mounted() {
    var vm = this;
    setTimeout(function() {
      vm.opacity = 1;
    }, 50);
  }
};
</script>
