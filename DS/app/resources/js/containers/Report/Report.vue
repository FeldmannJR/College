<template>
  <div class="report">
    <h1>Período</h1>
    <div class="periodFilter">
      <v-date-picker class="datePicker" v-model="startDate" :color="'#344669'" :reactive="true"></v-date-picker>
      <h2>Até</h2>
      <v-date-picker class="datePicker" v-model="endDate" :color="'#344669'" :reactive="true"></v-date-picker>
    </div>
    <h1>Indicadores</h1>
    <div class="indicatorList">
      <div v-for="indicator in indicators2" :key="indicator.id">
        <v-checkbox v-model="indicator.enabled" :label="indicator.name"></v-checkbox>
      </div>
    </div>

    <a href="/report/export">
      <v-btn id="generateReport" class="heButton">Gerar Relatório</v-btn>
    </a>
  </div>
</template>

<script>
require("./Report.scss");

export default {
  props: ["indicators"],
  data() {
    return {
      startDate: new Date().toISOString().substr(0, 10),
      endDate: new Date().toISOString().substr(0, 10),
      indicators2: this.indicators
    };
  }
};
</script>