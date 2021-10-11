<?php


namespace App\Indicators\Calculators\Base;


use App\SpreadsheetFile;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;

abstract class SpreadsheetCalculator extends IndicatorCalculator
{


    protected static $spreadsheetReader = null;

    public function canCalculate(): bool
    {
        $lastFile = SpreadsheetFile::getLastPath();
        if ($lastFile === null) {
            return false;
        }
        return true;
    }


    protected function getSpreadsheet()
    {
        return self::$spreadsheetReader;
    }

    /**
     * @param null $spreadsheetReader
     */
    public static function setSpreadsheetReader($spreadsheetReader): void
    {
        self::$spreadsheetReader = $spreadsheetReader;
    }

    /**
     * @param $function callable with argumenst $data(Carbon),$leito(string),$carater(string),$suspensa(string)
     */
    protected function forEachRowInCirurgia($function)
    {
        $reader = self::getSpreadsheet();
        $work = null;
        try {
            $reader->setActiveSheetIndex(3);
            $work = $reader->getActiveSheet();
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return false;
        }

        $data = 'A';
        $leito = 'B';
        $carater = 'C';
        $suspensa = 'D';

        $rowStart = 2;
        $maxRow = $work->getHighestRow($data);
        for ($row = $rowStart; $row <= $maxRow; $row++) {
            try {
                $data_val = $work->getCell("$data$row")->getValue();
                $date = Date::excelToDateTimeObject($data_val);
                if (!$date) {
                    continue;
                }
                $carbon = Carbon::instance($date);
                $leito_val = $work->getCell("$leito$row")->getValue();
                $carater_val = $work->getCell("$carater$row")->getValue();
                $suspensa_val = $work->getCell("$suspensa$row")->getValue();

                $function($carbon, $leito_val, $carater_val, $suspensa_val);

            } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
                continue;
            }
        }
        return true;
    }

    /**
     * @param $function callable with argumenst $data(Carbon),$leito(string),$carater(string),$suspensa(string)
     */
    protected function forEachRowInPartos($function)
    {
        $reader = self::getSpreadsheet();
        $work = null;
        try {
            $reader->setActiveSheetIndex(2);
            $work = $reader->getActiveSheet();
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return false;
        }
        $data = 'A';
        $tipo = 'B';

        $rowStart = 2;

        $maxRow = $work->getHighestRow($data);
        for ($row = $rowStart; $row <= $maxRow; $row++) {
            try {
                $data_val = $work->getCell("$data$row")->getValue();
                $date = Date::excelToDateTimeObject($data_val);
                if (!$date) {
                    continue;
                }
                $carbon = Carbon::instance($date);
                $tipo_val = $work->getCell("$tipo$row")->getValue();
                $function($carbon, $tipo_val);

            } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
                continue;
            }
        }
        return true;
    }

}