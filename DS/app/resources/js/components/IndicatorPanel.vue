<template>
  <div class="indicatorPanel">
    <!-- Renderiza indicador numerico -->
    <IndicatorNumeric
      v-if="i.type === 'numeric'"
      :indicator="i"
      :key="[cntnr, i.id].join('_')"
      :container="cntnr"
    />
    <template v-else>
      <!-- Renderiza indicador múltiplo -->
      <IndicatorMultiple
        v-if="indicator.graph === 'none'"
        :indicator="i"
        :stretched="stretched"
        :multiple="multiple"
        :key="[cntnr, i.id].join('_')"
        :container="cntnr"
      />
      <!-- Renderiza indicador múltiplo estatistico -->
      <IndicatorStatistic
        v-else
        :indicator="i"
        :stretched="stretched"
        :scale="scale"
        :key="[cntnr, i.id].join('_')"
        :container="cntnr"
      />
    </template>
  </div>
</template>
<script>
import IndicatorNumeric from "./IndicatorNumeric";
import IndicatorMultiple from "./IndicatorMultiple";
import IndicatorStatistic from "./IndicatorStatistic";

export default {
  props: ["indicator", "multiple", "stretched", "scale", "container"],
  components: {
    IndicatorNumeric,
    IndicatorMultiple,
    IndicatorStatistic
  },
  data() {
    return {
      i: this.indicator,
      cntnr: this.container || "indicatorContainer"
    };
  }
};
</script>
