<?php


namespace App\Indicators\Calculators;


use App\Indicators\Calculators\Base\SpreadsheetCalculator;
use App\Indicators\Indicator;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class NumeroPartosNaturaisCalculator extends SpreadsheetCalculator
{

    /**
     * Calcula o indicador de uma unidade especifica se a unidade for null
     * ele calcula o geral
     * @param Indicator $indicator
     * @param Carbon $timeToCalculate
     * @return double|double[] valor do indicador calculado ou map por unidade onde a key é o id da unidade e o valor é o numero calculado
     */
    public function calculateIndicator(Indicator $indicator, Carbon $timeToCalculate = null)
    {
        $sum = 0;
        $executed = $this->forEachRowInPartos(function ($data, $tipo) use (&$sum, $timeToCalculate) {
            if ($timeToCalculate->isSameDay($data)) {
                if ($tipo === 'PV') {
                    $sum++;
                }
            }
        });
        if ($executed) {
            return $sum;
        } else {
            return null;
        }
    }
}