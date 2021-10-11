<?php

namespace App\Indicators\Calculators;

use App\Enums\UpdateType;
use App\Indicators\Calculators\Base\IndicatorSQLCalculator;
use App\Indicators\Indicator;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NumeroLeitosDesocupadosCalculator extends IndicatorSQLCalculator
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
        $query ="select (select count(*) from agh.ain_leitos leitos where ind_situacao='A' and lto_id not in ('01-0121-A', '01-0121-B')) - (select count(distinct trim(int_t.lto_lto_id)) from agh.ain_internacoes int_t inner join agh.v_ain_internacao int
            on int_t.seq = int.nro_internacao where int.ind_paciente_internado='S' and lto_lto_id is not null) - (select sum(leitos_indisp) as total
            from agh.v_ain_leitos_indisp) ;";
        $rs = $this->getHeConnection()->selectOne($this->replaceDates($query, $data));
        return reset($rs);

    }
}
