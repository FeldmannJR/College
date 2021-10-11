<?php


namespace App\Indicators\Calculators;


use App\Indicators\Calculators\Base\SpreadsheetCalculator;
use App\Indicators\Indicator;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class TaxaDeSuspensaoDeCirurgiasCalculator extends SpreadsheetCalculator
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
        $cirurgias = 0;
        $suspensas = 0;
        $executed = $this->forEachRowInCirurgia(function ($data, $leito, $carater, $suspensa) use (&$cirurgias, &$suspensas, $timeToCalculate) {
            if ($timeToCalculate->isSameDay($data)) {
                if ($carater === "ENCAIXE" || $carater == "ELETIVA") {
                    $cirurgias++;
                }
                if ($suspensa === "SIM") {
                    $suspensas++;
                }
            }
        });
        if ($executed) {
            return $suspensas / max($cirurgias, 1);
        } else {
            return null;
        }
    }
}