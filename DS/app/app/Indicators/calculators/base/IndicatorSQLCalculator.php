<?php


namespace App\Indicators\Calculators\Base;


use App\Indicators\Indicator;
use App\Indicators\IndicatorsService;
use Carbon\Carbon;

abstract class IndicatorSQLCalculator extends IndicatorCalculator
{


    /**
     * @param string $query
     * @param \Carbon\Carbon $data to replace values
     * @return string the modified query
     */
    public function replaceDates(string $query, \Illuminate\Support\Carbon $data = null)
    {
        $data = $data ?? Carbon::now();
        $comecoMes = $data->copy()->startOfMonth()->toDateString();
        $fimMes = $data->copy()->endOfMonth()->toDateString();
        $last24h = $data->copy()->subDay();
        $variables = [
            "FIRST_DAY_MONTH" => "$comecoMes",
            "LAST_DAY_MONTH" => "$fimMes",
            "DATA" => "$data",
            "LAST_24H" => $last24h];
        foreach ($variables as $key => $value) {
            $query = str_replace('{' . strtoupper($key) . '}', $value, $query);
        }
        return $query;

    }

    protected function getHeConnection()
    {
        return resolve(IndicatorsService::class)->getHeConnection();
    }

}