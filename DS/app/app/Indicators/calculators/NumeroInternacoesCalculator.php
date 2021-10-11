<?php

namespace App\Indicators\Calculators;

use App\Enums\UpdateType;
use App\Indicators\Calculators\Base\IndicatorSQLCalculator;
use App\Indicators\Indicator;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NumeroInternacoesCalculator extends IndicatorSQLCalculator
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
        $query = "select count(*) as qtd_nova_internacao
                from agh.v_ain_internacao int inner join agh.ain_internacoes int_t on int_t.seq = int.nro_internacao
                inner join agh.agh_unidades_funcionais unidade  on unidade.seq = int.unidade_funcional
                where dthr_internacao >= '{LAST_24H}'   and unidade_funcional in (4,3,9,7,11,8,15,19,20,14) ;";
        $rs = $this->getHeConnection()->selectOne($this->replaceDates($query, $data));
        return reset($rs);

    }
}
