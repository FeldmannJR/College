<?php


namespace App\Indicators\Custom;


use App\Indicators\Calculators\Base\SpreadsheetCalculator;
use App\Indicators\Indicator;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class NumeroCirurgiasRealizadasCalculator extends SpreadsheetCalculator
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
        $executed = $this->forEachRowInCirurgia(function ($data, $leito, $carater, $suspensa) use (&$sum, $timeToCalculate) {
            if ($timeToCalculate->isSameDay($data)) {
                $sum++;
            }
        });
        if ($executed) {
            return $sum;
        } else {
            return null;
        }
    }
}