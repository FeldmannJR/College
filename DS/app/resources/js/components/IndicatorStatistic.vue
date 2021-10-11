<template>
  <div :id="id" class="indicatorStatistic" :stretched="stretched">
    <!-- Titulo do indicador -->
    <h2>{{indicator.text}}</h2>
    <div class="chart">
      <!-- Grafico que representa os valores -->
      <Chart
        :id="['chart', id].join('_')"
        :graph="indicator.graph"
        :datasets="datasets"
        :options="options"
      />
      <!-- Legenda -->
      <div class="legend">
        <div class="label" v-for="label in indicator.units" :key="label">
          <!-- Circulo com cor respectiva ao valor no grafico -->
          <div label :style="{ backgroundColor: colors[indicator.units.indexOf(label)] }" />
          <!-- Rotulo -->
          <h3>{{label}}</h3>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Chart from "./helpers/Chart";

export default {
  components: {
    Chart
  },
  props: ["indicator", "stretched", "scale", "container"],
  data() {
    const scale = this.scale || 1,
      fontSize = this.container == "panelContainer" ? 1.2 : 3;

    // Configuracoes opcionais do chart
    var options = {
      // Oculta legenda nativa do chart
      legend: {
        display: false
      },
      tooltips: {
        enabled: false
      },
      plugins: {
        // Plugin datalabels utilizado para exibir os valores em cima dos graficos
        datalabels: {
          anchor: "end",
          align: "start",
          color: "white",
          textShadowColor: "black",
          textShadowBlur: 4 * scale,
          font: {
            size: ((window.innerWidth * fontSize) / 100) * scale
          }
        }
      },
      elements: {
        arc: {
          borderWidth: 0
        }
      },
      scales: {}
    };

    // Ajusta intervalo de valores para graficos dos tipos barra e linha
    if (this.indicator.graph === "bar") {
      const maxValue = Math.max(...this.indicator.data);

      options.scales.yAxes = [
        {
          ticks: {
            fontSize: 10 * scale,
            suggestedMin: 0,
            max: maxValue * 1.5
          }
        }
      ];
    }

    return {
      id: [this.container, this.indicator.id].join("_"),
      options: options,
      // Esquema padrao de cores para diferenciar os dados
      colors: [
        "#344669",
        "#3C8376",
        "#3ec940",
        "#dde02c",
        "#dd9c2c",
        "#af3838",
        "#ff0000",
        "#89531e",
        "#92d12e",
        "#36d8d3"
      ]
    };
  },
  computed: {
    // Cria dataset com dados e esquema de cores
    datasets() {
      const data = this.indicator.data;

      if (this.indicator.graph === "bar") {
        return data.map((d, i) => {
          return { data: [d], backgroundColor: this.colors[i] };
        });
      } else {
        return [
          {
            data: data,
            backgroundColor: data.map((d, i) => {
              return this.colors[i];
            })
          }
        ];
      }
    }
  }
};
</script>
