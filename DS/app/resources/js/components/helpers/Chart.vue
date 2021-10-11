<template>
  <canvas :id="singleId" :graph="graph"></canvas>
</template>
<script>
export default {
  // Helper para criar charts utilizando Chart.js

  props: ["id", "graph", "labels", "datasets", "options"],
  data() {
    return {
      chart: null,
    };
  },
  created() {
    this.singleId = document.getElementById(this.id) ? this.id + "_" : this.id;
  },
  mounted() {
    // Renderiza o chart no canvas indicado pelo id passado
    this.ctx = document.getElementById(this.singleId).getContext("2d");
    this.chart = new Chart(this.ctx, this.chartStructure);
  },
  computed: {
    chartStructure() {
      return {
        // Tipo de grafico
        type: this.graph || "bar",

        data: {
          // Legendas
          labels: this.labels,
          // Valores
          datasets: this.datasets
        },

        // Configuracoes opcionais
        options: this.options
      };
    }
  },
  updated() {
    this.chart = new Chart(this.ctx, this.chartStructure);
  }
};
</script>
