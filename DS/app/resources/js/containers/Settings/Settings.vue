<template>
  <div class="settings">
    <h1 class="header">Configuração do painel</h1>
    <div class="slides">
      <div class="slidesList" :class="hide">
        <v-data-iterator
          1
          :items="sortedPresentation"
          :rows-per-page-items="[4]"
          :pagination.sync="slideListPagination"
          content-tag="v-layout"
        >
          <template v-slot:item="props">
            <div class="slidePreview">
              <div class="slideOptions">
                <v-tooltip left>
                  <template v-slot:activator="{ on }">
                    <v-icon
                      v-on="on"
                      v-if="!(sortedPresentation.indexOf(props.item) == 0)"
                      @click="formatOrder(sortedPresentation.indexOf(props.item), sortedPresentation.indexOf(props.item))"
                    >arrow_upward</v-icon>
                  </template>
                  <span>Mover acima</span>
                </v-tooltip>
                <v-tooltip left>
                  <template v-slot:activator="{ on }">
                    <v-icon
                      v-on="on"
                      @click="deleteSlide(sortedPresentation.indexOf(props.item))"
                    >delete</v-icon>
                  </template>
                  <span>Excluir Slide</span>
                </v-tooltip>
                <v-tooltip left>
                  <template v-slot:activator="{ on }">
                    <v-icon
                      v-on="on"
                      v-if="!(sortedPresentation.indexOf(props.item) == sortedPresentation.length - 1)"
                      @click="formatOrder(sortedPresentation.indexOf(props.item) + 2, sortedPresentation.indexOf(props.item))"
                    >arrow_downward</v-icon>
                  </template>
                  <span>Mover abaixo</span>
                </v-tooltip>
              </div>
              <div @click="currentSlide = sortedPresentation.indexOf(props.item)">
                <Panel
                  :fixed="fixed"
                  :presentation="sortedPresentation"
                  :scale=".09"
                  :container="'slidePreview_' + sortedPresentation.indexOf(props.item)"
                  :index="sortedPresentation.indexOf(props.item)"
                  :stop="true"
                  :selected="currentSlide == sortedPresentation.indexOf(props.item)"
                  :key="props.item.order"
                />
              </div>
            </div>
          </template>
        </v-data-iterator>
        <v-btn class="btnAdd" @click="addSlide">
          <v-icon>add</v-icon>Adicionar Slide
        </v-btn>
      </div>
      <Panel
        :fixed="fixed"
        :presentation="sortedPresentation"
        :scale=".5"
        :index="currentSlide"
        :stop="true"
        :container="'slideBigPreview'"
        :class="hide"
      />

      <v-form class="slideSettings">
        <v-select
          v-for="n in [0, 1, 2, 3]"
          :label="'Indicador ' + (n + 1)"
          :items="availableIndicators"
          :placeholder="getIndicatorName(n)"
          :value="getIndicatorName(n)"
          :disabled="getSlide().slide.flat().length < n"
          prepend-icon="show_chart"
          :key="n"
          @input="rearrangeSlide($event, n)"
        />
        <v-text-field
          id="timerInput"
          label="Tempo"
          v-model="sortedPresentation[currentSlide].timer"
          type="number"
          min="0"
          max="3600"
          prepend-icon="alarm"
          @keypress="checkTimer"
          @input="formatTimer"
        ></v-text-field>
        <v-select
          label="Ordem"
          v-model="sortedPresentation[currentSlide].order"
          :items="order"
          prepend-icon="swap_vert"
          @input="formatOrder"
        ></v-select>
        <v-btn
          class="btnSave"
          :disabled="!updatedPresentation || savingPresentation"
          :loading="savingPresentation"
          @click="savePresentation"
        >
          <v-icon>save</v-icon>Salvar
        </v-btn>
      </v-form>
    </div>
    <h1 class="header">Indicadores</h1>
    <div class="indicatorsList">
      <div class="indicator" v-for="indicator in localIndicators" :key="indicator.name">
        <div class="preview">
          <IndicatorPanel :indicator="indicator" :scale="0.175" />
        </div>
        <div class="indicatorSettings">
          <h4>{{indicator.name}}</h4>
          <v-form>
            <v-text-field label="Texto" prepend-icon="title" v-model="indicator.text" />
            <v-select
              v-if="indicator.graph"
              v-model="indicator.graph"
              label="Gráfico"
              :items="Object.keys(graphs).map((graph) => {
                return { text: graphs[graph], value: graph }
              })"
              prepend-icon="bar_chart"
            ></v-select>
          </v-form>
        </div>
        <div
          class="save"
          :disabled="!updatedIndicator(localIndicators.indexOf(indicator)) || savingIndicator"
        >
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-icon
                color="primary"
                :disabled="!updatedIndicator(localIndicators.indexOf(indicator))"
                v-on="on"
                @click="saveIndicator(localIndicators.indexOf(indicator))"
              >save</v-icon>
            </template>
            <span>Salvar</span>
          </v-tooltip>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
require("./Settings.scss");
import IndicatorPanel from "../../components/IndicatorPanel";
import Panel from "../Panel/Panel";
import { setTimeout } from "timers";
import { createHash } from "crypto";

export default {
  components: {
    IndicatorPanel,
    Panel
  },
  props: ["fixed", "presentation", "indicators"],
  data() {
    var sortedPresentation = this.presentation.sort((a, b) => {
      return a.order < b.order ? -1 : 1;
    });

    return {
      savingPresentation: false,
      savingIndicator: false,
      currentSlide: 0,
      slideListPagination: {
        page: 1,
        totalItems: 2
      },
      graphs: {
        bar: "Barras",
        pie: "Setores",
        doughnut: "Rosca",
        none: "Nenhum"
      },
      sortedPresentation: sortedPresentation,
      localIndicators: this.indicators,
      originalIndicators: this.indicators.map(i => ({ ...i })),
      originalPresentation: null,
      hide: ""
    };
  },
  computed: {
    updatedPresentation() {
      return (
        JSON.stringify(this.originalPresentation) !=
        JSON.stringify(this.sortedPresentation)
      );
    },
    availableIndicators() {
      return [
        this.localIndicators
          .filter(i => {
            return !this.getSlide()
              .slide.flat()
              .includes(i);
          })
          .map(i => {
            return { text: i.name, value: i.id };
          }),
        { text: "Nenhum", value: null }
      ].flat();
    },
    order() {
      return this.sortedPresentation.map(p => {
        return p.order;
      });
    }
  },
  methods: {
    addSlide() {
      this.sortedPresentation.push({
        timer: 3,
        order: this.sortedPresentation.length + 1,
        slide: [[]]
      });

      this.currentSlide = this.sortedPresentation.length - 1;
      this.slideListPagination.page = Math.floor(this.currentSlide / 4) + 1;
    },
    getSlide(index = this.currentSlide) {
      return this.sortedPresentation[index];
    },
    deleteSlide(index) {
      var current = this.currentSlide;
      this.formatOrder(this.sortedPresentation.length, index);
      this.sortedPresentation.splice(index, 1);
      this.currentSlide = Math.min(current, this.sortedPresentation.length - 1);

      if (this.sortedPresentation.length == 0) {
        this.addSlide();
      } else if (current == index) {
        this.forceRender();
      }
    },
    savePresentation() {
      let jsonString = JSON.stringify(this.sortedPresentation);
      this.savingPresentation = true;
      axios
        .post("/presentation/save", { presentation: jsonString })
        .then(this.successSavePresentation)
        .catch(this.errorSavePresentation);
    },
    successSavePresentation(response) {
      let presentation = response.data.presentation;
      this.originalPresentation = JSON.parse(
        JSON.stringify(this.sortedPresentation)
      );
      this.savingPresentation = false;
    },
    errorSavePresentation(error) {
      if (error.response) {
        let data = error.response.data;
        if (typeof data.errors !== "undefined") {
          let errors = data.errors;
          for (let field in errors) {
            // Mostrar isso em algum lugar
            //field = qual o campo que n validou nesse caso só tem presentation
            //errors[field] = retorna uma array de strings com os erros
            console.log("ERROR: " + field + " - " + errors[field]);
          }
        }
      }
      this.savingPresentation = false;
    },
    rearrangeSlide(id, index) {
      const indicator = this.localIndicators.filter(i => {
        return i.id == id;
      })[0];
      let slide = this.getSlide().slide.flat();
      index;

      if (indicator) {
        slide[index] = indicator;
      } else {
        slide.splice(index, 1);
      }

      var formattedSlide = [[]];
      for (var i = 0; i < 2; ++i) {
        if (slide[i]) {
          formattedSlide[0].push(slide[i]);
        }
      }
      if (slide[2]) {
        formattedSlide.push([]);
        for (var i = 2; i < 4; ++i) {
          if (slide[i]) {
            formattedSlide[1].push(slide[i]);
          }
        }
      }

      this.getSlide().slide = formattedSlide;

      this.forceRender();
    },
    forceRender() {
      for (var i = 0; i < 5; ++i) {
        this.sortedPresentation.push({
          timer: 0,
          order: this.sortedPresentation.length,
          slide: this.getSlide().slide
        });
      }

      this.hide = "hide";
      this.currentSlide += 5;
      const vm = this;
      setTimeout(function() {
        document.querySelector('[aria-label="Next page"]').click();
        setTimeout(function() {
          document.querySelector('[aria-label="Previous page"]').click();
          vm.currentSlide -= 5;
          vm.sortedPresentation.splice(-5, 5);
          vm.hide = "";
        }, 0.1);
      }, 0.1);
    },
    getIndicatorName(n) {
      return this.getSlide().slide[Math.floor(n / 2)] &&
        this.getSlide().slide[Math.floor(n / 2)][n % 2]
        ? this.getSlide().slide[Math.floor(n / 2)][n % 2].name
        : null;
    },
    checkTimer(e) {
      if (e.key < "0" || e.key > "9") {
        e.preventDefault();
      }
    },
    formatTimer() {
      var timer = this.getSlide().timer,
        value =
          timer
            .split("")
            .filter(char => {
              return char >= "0" && char <= "9";
            })
            .join("") || 0;
      timer = parseInt(value);
    },
    formatOrder(newOrder, order) {
      if (order != null) {
        this.getSlide(order).order = newOrder;
      } else {
        order = this.currentSlide;
      }

      newOrder--;
      if (order < newOrder) {
        for (var i = newOrder; i > order; i--) {
          this.getSlide(i).order -= 1;
        }
      } else {
        for (var i = newOrder; i < order; i++) {
          this.getSlide(i).order += 1;
        }
      }

      this.currentSlide = this.currentSlide == order ? newOrder : order;
    },
    updatedIndicator(index) {
      return (
        JSON.stringify(this.localIndicators[index]) !=
        JSON.stringify(this.originalIndicators[index])
      );
    },
    saveIndicator(index) {
      this.savingIndicator = true;
      let indicator = this.localIndicators[index];
      let type =
        typeof indicator.graph === "undefined" || indicator.graph === "none"
          ? indicator.type
          : indicator.graph;
      axios
        .post("/indicators/update", {
          id: indicator.id,
          display_name: indicator.text,
          display_type: type
        })
        .then(this.successSaveIndicator(index))
        .catch(this.errorSaveIndicator(index));
    },
    successSaveIndicator(index) {
      // Ja tá setando em cima igual
      return response => {
        this.savingIndicator = false;
        this.originalIndicators[index] = JSON.parse(
          JSON.stringify(this.localIndicators[index])
        );
      };
    },
    errorSaveIndicator(index) {
      return error => {
        if (error.response) {
          let data = error.response.data;
          if (typeof data.errors !== "undefined") {
            let errors = data.errors;
            for (let field in errors) {
              // Mostrar isso em algum lugar
              //field = qual o campo que n validou
              //errors[field] = retorna uma array de strings com os erros
              console.log("ERROR: " + field + " - " + errors[field]);
            }
          }
        }
        this.savingIndicator = false;
      };
    }
  },
  created() {
    if (this.sortedPresentation.length > 0) {
      this.sortedPresentation = this.sortedPresentation.map(p => {
        return {
          timer: p.timer,
          order: p.order,
          slide: p.slide.map(row => {
            return row.map(i => {
              return this.localIndicators.filter(ind => {
                return ind.id == i;
              })[0];
            });
          })
        };
      });
    } else {
      this.addSlide();
    }

    this.originalPresentation = this.sortedPresentation.map(p => ({ ...p }));
  }
};
</script>