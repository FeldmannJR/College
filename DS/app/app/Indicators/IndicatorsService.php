<?php


namespace App\Indicators;


use App\Console\Commands\UpdateIndicators;
use App\Enums\UpdateType;
use App\IndicatorHistory;
use App\Indicators\Calculators\Base\IndicatorCalculator;
use App\Indicators\Calculators\SingleQueryCalculator;
use App\Indicators\Calculators\Base\SpreadsheetCalculator;
use App\SpreadsheetFile;
use App\Unit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use RuntimeException;


class IndicatorsService
{

    private $calculators = [];

    public function truncate()
    {
        IndicatorHistory::truncate();
        Indicator::truncate();
        IndicatorQuery::truncate();
    }

    /**
     * Carrega os indicadores do banco, caso $updateType tenha um valor será filtrado por ele
     * @param UpdateType|null $updateType filtro de quais indicadores vai carregar
     * @return Indicator[] array com todos os indicadores
     */
    public function load(UpdateType $updateType = null)
    {
        if ($updateType === null) {
            return Indicator::all()->all();
        } else {
            return Indicator::where('update_type', $updateType->value)->get()->all();
        }
    }


    /**
     * @param Indicator $indicator
     * @param float $value valor a ser adicionado
     * @param Unit|null $unit se o valor está atrelado a alguma unidade
     * @param null $timestamp modificar a hora que foi adicionado
     */
    public function addHistoryValue(Indicator $indicator, float $value, Unit $unit = null, $timestamp = null)
    {
        $whenAdded = now();
        if ($timestamp !== null) {
            $whenAdded = $timestamp;
        }

        $history = new IndicatorHistory(['value' => $value, 'created_at' => $whenAdded]);
        $history->indicator()->associate($indicator);
        if ($unit !== null) {
            $history->unit()->associate($unit);
        }
        $history->save();
    }

    /**
     *
     * @param String $name
     * @param UpdateType $update_type
     * @param String $calculator
     * @param bool $per_unit
     * @return Indicator
     */
    public function addIndicator(String $name, UpdateType $update_type, String $calculator, bool $per_unit)
    {
        if (Indicator::where('name', $name)->exists()) {
            throw new RuntimeException('Já existe indicador com o nome ' . $name);
        }
        $ind = new Indicator([
            'name' => $name,
            'update_type' => $update_type->value,
            'class' => $calculator,
            'per_unit' => $per_unit
        ]);
        $ind->save();
        return $ind;
    }

    /**
     * @param string $name nome do indicador
     * @param UpdateType $updateType frequencia de update do indicador
     * @param string $query query que irá executar para calcular o valor
     * @return Indicator
     */
    public function addSingleQueryIndicator(string $name, UpdateType $updateType, string $query)
    {
        $indicator = $this->addIndicator($name, $updateType, SingleQueryCalculator::class, false);
        if ($indicator === null) {
            return null;
        }
        $indicator->indicatorQuery()->save(new IndicatorQuery(['sql_query' => $query]));
        return $indicator;

    }


    /**
     * @param string $class Classe do calculador
     * @return null|IndicatorCalculator calculator
     */
    public function getCalculator(string $class)
    {
        if (!class_exists($class)) {
            return null;
        }
        if (!array_key_exists($class, $this->calculators)) {
            $this->calculators[$class] = new $class;
        }
        return $this->calculators[$class];
    }

    public function calculateAndSaveAll(UpdateType $updateType = null, Carbon $data = null, UpdateIndicators $command = null)
    {
        if ($data === null)
            $data = Carbon::create(2019, 2, 20);
        $indicators = $this->load($updateType);

        // Instanciando uma vez somente pra não usar memoria desnecessária
        $spreadsheet = self::getSpreadsheetReader();

        SpreadsheetCalculator::setSpreadsheetReader($spreadsheet);

        foreach ($indicators as $indicator) {
            $success = $indicator->calculateAndSave($data, $command !== null ? array($command, 'info') : null);

            if (!$success) {
                $msg = 'Não consegui calcular ' . $indicator->name;
                if ($command !== null) {
                    $command->error($msg);
                } else {
                    echo($msg . '<br>');
                }
            } else {
                $indicator->last_update = now();
                $indicator->save();

            }
        }
    }

    private function getSpreadsheetReader()
    {
        $lastFile = SpreadsheetFile::getLastPath();
        if ($lastFile === null) return null;
        $reader = new Xlsx();
        $reader->setReadDataOnly(true);
        try {
            $spreadsheet = $reader->load($lastFile);
            return $spreadsheet;
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            return null;
        }
    }

    /**
     * @return \Illuminate\Database\ConnectionInterface
     */
    public function getHeConnection()
    {
        return DB::connection("pgsql_agh");
    }

}


?>