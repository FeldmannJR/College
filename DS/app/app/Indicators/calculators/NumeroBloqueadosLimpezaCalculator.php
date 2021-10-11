<?php

namespace App\Indicators\Calculators;

use App\Enums\UpdateType;
use App\Indicators\Calculators\Base\IndicatorSQLCalculator;
use App\Indicators\Indicator;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NumeroBloqueadosLimpezaCalculator extends IndicatorSQLCalculator
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
        $query = "select count(*) from agh.v_ain_leitos_limpeza;";
        $rs = $this->getHeConnection()->selectOne($this->replaceDates($query, $data));
        return reset($rs);

    }
}
