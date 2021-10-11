<?php


namespace App\Indicators\Calculators\Base;


use App\Indicators\Indicator;
use Carbon\Carbon;

abstract class IndicatorCalculator
{

    /**
     * Retorna se o calculator tem o que precisa para realizar os calculos
     * @return bool
     */
    public function canCalculate(): bool
    {
        return true;
    }

    public function convert($value)
    {
        return null;
    }

    /**
     * Calcula o indicador de uma unidade especifica se a unidade for null
     * ele calcula o geral
     * @param Indicator $indicator indicador para ser calculado
     * @param Carbon $timeToCalculate
     * @return double|double[] valor do indicador calculado ou map por unidade onde a key é o id da unidade e o valor é o numero calculado
     */
    abstract public function calculateIndicator(Indicator $indicator, Carbon $timeToCalculate = null);


}