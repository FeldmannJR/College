<?php

use App\Enums\UpdateType;
use App\Indicators\Calculators\MediaPermanenciaGeralCalculator;
use App\Indicators\Calculators\NumeroAltasCalculator;
use App\Indicators\Calculators\NumeroBloqueadosLimpezaCalculator;
use App\Indicators\Calculators\NumeroInternacoesCalculator;
use App\Indicators\Calculators\NumeroLeitosDesocupadosCalculator;
use App\Indicators\Calculators\NumeroPacientesInternadosCalculator;
use App\Indicators\Calculators\NumeroPartosNaturaisCalculator;
use App\Indicators\Calculators\OcupacaoPorUnidadeCalculator;
use App\Indicators\Calculators\RotatividadeLeitosCalculator;
use App\Indicators\Calculators\TaxaDeSuspensaoDeCirurgiasCalculator;
use App\Indicators\Calculators\TaxaMortalidadeGeralCalculator;
use App\Indicators\Calculators\TaxaOcupacaoGeralCalculator;
use App\Indicators\Custom\NumeroCirurgiasRealizadasCalculator;
use App\Indicators\Custom\NumeroPartosCesareosCalculator;
use App\Indicators\Indicator;
use App\Indicators\IndicatorsService;
use App\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class IndicatorTableSeeder extends Seeder
{
    /** @var IndicatorsService */
    private $service = null;

    /**
     * IndicatorTableSeeder constructor.
     */
    public function __construct()
    {
        $this->service = resolve(IndicatorsService::class);;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Remove os indicadores antigos pra não dar conflito
        $this->service->truncate();
        // Copia as unidades para a nossa tabela
        $this->populateUnits();


        $this->addIndicator('Numero de leitos bloqueados para limpeza', UpdateType::RealTime(), NumeroBloqueadosLimpezaCalculator::class);
        $this->addIndicator('Número de Internações', UpdateType::Daily(), NumeroInternacoesCalculator::class);
        $this->addIndicator('Número de altas', UpdateType::Daily(), NumeroAltasCalculator::class);
        $this->addIndicator("Taxa de Mortalidade Geral", UpdateType::RealTime(), TaxaMortalidadeGeralCalculator::class);
        $this->addIndicator('Número de leitos desocupados', UpdateType::RealTime(), NumeroLeitosDesocupadosCalculator::class);
        $this->addIndicator('Número de pacientes internados', UpdateType::RealTime(), NumeroPacientesInternadosCalculator::class);
        $this->addIndicator('Média de permanência geral', UpdateType::Monthly(), MediaPermanenciaGeralCalculator::class);
        $this->addIndicator('Ocupação por Unidade', UpdateType::RealTime(), OcupacaoPorUnidadeCalculator::class, true);
        $this->addIndicator('Rotatividade de Leitos', UpdateType::Monthly(), RotatividadeLeitosCalculator::class, true);
        $this->addIndicator('Número de Cirurgias Realizadas', UpdateType::Daily(), NumeroCirurgiasRealizadasCalculator::class);
        $this->addIndicator('Taxa de suspensão de cirurgias', UpdateType::Daily(), TaxaDeSuspensaoDeCirurgiasCalculator::class);
        $this->addIndicator('Número de partos naturais', UpdateType::Daily(), NumeroPartosNaturaisCalculator::class);
        $this->addIndicator('Número de partos cesáreos', UpdateType::Daily(), NumeroPartosCesareosCalculator::class);
        $this->addIndicator('Taxa de Ocupação Geral', UpdateType::RealTime(), TaxaOcupacaoGeralCalculator::class);
        /*

        // Planilhas
        $this->addIndicator(new IndicatorNumeroCirurgiasRealizadas(null, "Número de Cirurgias realizadas", UpdateType::Daily()));
        $this->addIndicator(new IndicatorTaxaDeSuspensaoDeCirurgias(null, "Taxa de suspensão de cirurgias", UpdateType::Daily()));
        $this->addIndicator(new IndicatorNumeroPartosNaturais(null, "Número de partos naturais", UpdateType::Daily()));
        $this->addIndicator(new IndicatorNumeroPartosCesareos(null, "Número de partos cesáreos", UpdateType::Daily()));
        */

        if ($this->command->confirm('Você deseja popular o historico com valores aleatorios?', false)) {
            foreach ($this->service->load() as $indicator) {
                $this->addExampleData($indicator);
            }
        }
    }

    /**
     * Copia as unidades do banco do HE para a nossa tabela no datamart
     */
    private function populateUnits()
    {
        Unit::truncate();
        $units = $this->service->getHeConnection()->table("agh.agh_unidades_funcionais")->get();
        foreach ($units as $unit) {
            $newUnit = new Unit();
            $newUnit->id = $unit->seq;
            $newUnit->code = $unit->sigla;
            $newUnit->name = $unit->descricao;
            $newUnit->save();
        }
    }

    /**
     * @param $name
     * @param UpdateType $updateType
     * @param string $query
     */
    private function addSimple($name, UpdateType $updateType, string $query)
    {
        $indicador = $this->service->addSingleQueryIndicator($name, $updateType, $query);

    }

    /**
     * Adicionado um indicador ao banco
     * @param string $name
     * @param UpdateType $updateType
     * @param string $calculatorClass
     * @param bool $per_unit
     */
    private function addIndicator(string $name, UpdateType $updateType, string $calculatorClass, bool $per_unit = false)
    {
        $id = $this->service->addIndicator($name, $updateType, $calculatorClass, $per_unit);
    }

    /**
     * @param IndicatorOld $indicator
     */
    private function addExampleData(Indicator $indicator)
    {
        // Inserting sample data
        $rnd = rand(30, 100);
        $this->command->info("Inserting $rnd values into indicator " . $indicator->getName());
        for ($i = 0; $i < $rnd; $i++) {
            $value = $this->random_float(0, 500000);
            $timestamp = Carbon::now()->subMinutes(rand(1, 60 * 24 * 30 * 12 * 3));
            $unit = null;
            if ($indicator->isPerUnit()) {
                $unit = Unit::getById(Unit::$displayUnits[array_rand(Unit::$displayUnits)]);
            }
            $this->service->addHistoryValue($indicator, $value, $unit, $timestamp);
        }
    }

    private function random_float($min, $max)
    {
        return ($min + lcg_value() * (abs($max - $min)));
    }


}
