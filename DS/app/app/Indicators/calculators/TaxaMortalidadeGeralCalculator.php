<?php

namespace App\Indicators\Calculators;

use App\Enums\UpdateType;
use App\Indicators\Calculators\Base\IndicatorSQLCalculator;
use App\Indicators\Indicator;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TaxaMortalidadeGeralCalculator extends IndicatorSQLCalculator
{

    public function convert($value)
    {
        return (number_format($value, 0));
    }

    /**
     * Calcula o indicador de uma unidade especifica se a unidade for null
     * ele calcula o geral
     * @param Indicator $indicator
     * @param Carbon|null $data
     * @return double valor do indicador calculado
     */
    public function calculateIndicator(Indicator $indicator, Carbon $data = null)
    {
        $query = "select count(*)
            from agh.v_ain_internacao int inner join agh.ain_internacoes int_t on int_t.seq = int.nro_internacao 
            inner join agh.v_ain_internacao_paciente pac on int.nro_internacao = pac.seq 
            inner join agh.agh_unidades_funcionais unidade on unidade.seq = int.unidade_funcional 
            inner join agh.ain_leitos leito on leito.qrt_numero = int.qrt_numero and leito.leito = int.leito 
            inner join agh.ain_tipos_alta_medica motivo on motivo.codigo = int_t.tam_codigo 
            where motivo.codigo in ('C', 'D', 'O') and unidade.seq in (4,3,9,7,11,8,15,19,20,14)
            and int_t.dthr_alta_medica>= '{LAST_24H}';";
        $rs = $this->getHeConnection()->selectOne($this->replaceDates($query, $data));
        return reset($rs);

    }
}
