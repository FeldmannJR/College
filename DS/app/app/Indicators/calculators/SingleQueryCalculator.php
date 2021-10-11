<?php

namespace App\Indicators\Calculators;

use App\Enums\UpdateType;
use App\Indicators\Calculators\Base\IndicatorSQLCalculator;
use App\Indicators\Indicator;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SingleQueryCalculator extends IndicatorSQLCalculator
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
        if (!$indicator->indicatorQuery()->exists()) {
            return null;
        }
        if (!$indicator->isPerUnit()) {
            $rs = $this->getHeConnection()->selectOne($this->replaceDates($indicator->indicatorQuery->sql_query, $data));
            // Retorna o primeiro elemento do objeto
            return reset($rs);
        }
        return null;
    }
}
