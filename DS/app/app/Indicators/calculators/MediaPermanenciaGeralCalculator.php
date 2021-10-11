<?php


namespace App\Indicators\Calculators;


use App\Enums\UpdateType;
use App\Indicators\Calculators\Base\IndicatorSQLCalculator;

use App\Indicators\Indicator;
use App\Unit;
use Carbon\Carbon;

class MediaPermanenciaGeralCalculator extends IndicatorSQLCalculator
{


    /**
     * Calcula o indicador de uma unidade especifica se a unidade for null
     * ele calcula o geral
     * @param Indicator $indicator
     * @param Carbon|null $data
     * @return double valor do indicador calculado
     */
    public function calculateIndicator(Indicator $indicator, Carbon $data = null)
    {

        $qry = "select extract(epoch from sum(
                   (case when dt_saida_paciente > '{LAST_DAY_MONTH}' then '{LAST_DAY_MONTH}'
                         when dt_saida_paciente is null then '{LAST_DAY_MONTH}'
                         else dt_saida_paciente
                       end  ) -
                   (case when data < '{FIRST_DAY_MONTH}' then '{FIRST_DAY_MONTH}'
                         else data
                       end )))
                    from agh.v_ain_internacao int inner join agh.ain_internacoes int_t on int_t.seq = int.nro_internacao
                              inner join agh.agh_unidades_funcionais unidade  on unidade.seq = int.unidade_funcional
                    where (dt_saida_paciente is null or dt_saida_paciente>= '{FIRST_DAY_MONTH}') and data<='{LAST_DAY_MONTH}' and unidade_funcional in (4,3,9,7,11,8,15,19,20,14);";

        $qry2 = "select count(*) 
                    from agh.v_ain_internacao int inner join agh.ain_internacoes int_t on int_t.seq = int.nro_internacao 
                    inner join agh.agh_unidades_funcionais unidade  on unidade.seq = int.unidade_funcional
                    where dt_saida_paciente>='{FIRST_DAY_MONTH}' and dt_saida_paciente<='{LAST_DAY_MONTH}' and unidade_funcional in (4,3,9,7,11,8,15,19,20,14) ;";


        $pacientes_dia = $this->getHeConnection()->selectOne($this->replaceDates($qry, $data));

        $numeros_saida = $this->getHeConnection()->selectOne(
            $this->replaceDates(
                $qry2,
                $data));

        $pacientes_dia = reset($pacientes_dia) / 86400;
        $numeros_saida = reset($numeros_saida);
        return $pacientes_dia / max($numeros_saida, 1);
    }
}