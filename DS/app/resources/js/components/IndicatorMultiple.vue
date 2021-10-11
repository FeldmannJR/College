<template>
  <div class="indicatorMultiple" :style="{opacity: opacity}">
    <!-- Titulo do indicador -->
    <h4 class="text">{{indicator.text}}</h4>
    <div class="units">
      <div class="unit" v-for="unit in indicator.units" :key="unit">
        <h4 class="unitName">{{unit}}</h4>
        <h4 class="unitValue">{{indicator.data[indicator.units.indexOf(unit)]}}</h4>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["indicator"],
  data() {
    return {
      i: this.indicator,
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
