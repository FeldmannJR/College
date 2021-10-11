<template>
  <div class="panel">
    <div class="fixed">
      <IndicatorFixed
        v-for="indicator in fixed"
        :indicator="indicator"
        :key="fixed.indexOf(indicator)"
      />
    </div>
    <Slider
      v-if="presentation.length > 0"
      :key="index"
      :indicators="sortedPresentation[index].slide"
      :scale="scale || 1"
      :container="container || 'panelContainer'"
    />
  </div>
</template>

<script>
require("./Panel.scss");
import IndicatorFixed from "../../components/IndicatorFixed";
import Slider from "../../components/Slider";

export default {
  props: [
    "fixed",
    "presentation",
    "indicators",
    "scale",
    "container",
    "index",
    "stop"
  ],
  components: {
    IndicatorFixed,
    Slider
  },
  data() {
    return {
      sortedPresentation: this.presentation.sort((a, b) => {
        return a.order < b.order ? -1 : 1;
      })
    };
  },
  methods: {
    // Avança 1 slide
    changeSlide() {
      if (!this.stop) {
        this.index = (this.index + 1) % this.sortedPresentation.length;
        setTimeout(
          this.changeSlide,
          this.sortedPresentation[this.index].timer * 1000
        );
      }
    }
  },
  created() {
    this.index = this.index || 0;

    if (this.sortedPresentation.length > 0) {
      this.sortedPresentation = this.sortedPresentation.map(p => {
        return {
          timer: p.timer,
          order: p.order,
          slide: p.slide.map(row => {
            return row.map(i => {
              return this.indicators.filter(ind => {
                return ind.id == i;
              })[0];
            });
          })
        };
      });

      setTimeout(
        this.changeSlide,
        this.sortedPresentation[this.index].timer * 1000
      );
    }
  }
};
</script>