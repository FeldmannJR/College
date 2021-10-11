<?php

namespace App\Indicators;

use App\Enums\UpdateType;
use App\IndicatorHistory;
use App\Indicators\calculators\IndicatorCalculator;
use App\Unit;
use BenSampo\Enum\Traits\CastsEnums;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    use CastsEnums;

    protected $guarded = [];

    protected $enumCasts = ['update_type' => UpdateType::class];

    protected $dates = ['last_update'];

    public function indicatorQuery()
    {
        return $this->hasOne(IndicatorQuery::class, "id", "id");
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUpdateType()
    {
        return $this->update_type;
    }

    public function history()
    {
        return $this->hasMany(IndicatorHistory::class);
    }

    /**
     * Calcula o valor do indicador e salva no banco este valor
     * @param Carbon|null $data em qual data será calculado os indicadors
     * @param $output
     * @return bool
     */
    public function calculateAndSave(Carbon $data = null, $output = null)
    {

        if ($output == null) {
            $output = function ($strg) {
                echo $strg . '<br/>';
            };
        }

        $service = resolve(IndicatorsService::class);
        // Procura o calculator do indicador
        $calculator = $this->getCalculator();
        // Checa se ele tem o que precisa pra calcular(ex planilha)
        if (!$calculator->canCalculate()) {
            return null;
        }
        // Calcula o valor do indicador
        $value = $calculator->calculateIndicator($this, $data);
        if ($value === null) {
            return null;
        }
        // Verifica se esse indicador possui valores por unidade
        if ($this->isPerUnit()) {
            $allUnits = Unit::getAllUnits();
            // Percorre todas as unidades que foram calculadas
            foreach ($value as $unit_id => $unit_value) {
                // Verifica se a chave é um inteiro e o valor um numerico, e verifica se a unidade existe no nosso banco
                if (is_int($unit_id) && is_numeric($unit_value) && array_key_exists($unit_id, $allUnits)) {
                    $output("Calculated $this->name in unit $unit_id to $unit_value");
                    // Adiciona o valor ao banco
                    $service->addHistoryValue($this, $unit_value, $allUnits[$unit_id]);
                }
            }
            return true;
        } else {
            if (is_numeric($value)) {
                $output("Calculated $this->name to $value");
                $service->addHistoryValue($this, $value);
                return true;
            }
        }
        return null;
    }

    /**
     * @param Unit|null $unit de qual unidade irá buscar o valor, caso seja null vai pegar o geral
     * @return double|null ultimo valor calculado
     */
    public function getLastValue(Unit $unit = null)
    {
        $rs = null;
        if ($unit === null) {
            $rs = $this->history()->doesntHave('unit')->orderBy('created_at', 'desc')->first();
        } else {
            $rs = $this->history()->where('unit_id', $unit->id)->orderBy('created_at', 'desc')->first();
        }

        if ($rs != null) {
            return $rs->value;
        }
        return null;
    }

    /**
     * @param Unit|null $unit unidade para ser calculado o valor
     * @return string|null valor a ser mostrado na view
     */
    public function getDisplayLastValue(Unit $unit = null): ?string
    {
        $value = $this->getLastValue($unit);
        if ($value === null) {
            return 'Sem dados';
        } else {
            $format = $this->getCalculator()->convert($value);
            if ($format === null)
                return number_format($value, 2);
            return $format;
        }
    }

    /**
     * @return string|null valor a ser mostrado na view
     */
    public function getDisplayLastUpdate(): ?string
    {
        if ($this->last_update === null) {
            return 'Nunca';
        } else {
            return $this->last_update->format('m/d/Y h:i:s');
        }
    }


    /**
     * @return null|IndicatorCalculator
     */
    public function getCalculator()
    {
        $service = resolve(IndicatorsService::class);
        // Procura o calculator do indicador
        return $service->getCalculator($this->getCalculatorClass());
    }

    /**
     * @return IndicatorCalculator
     */
    public function getCalculatorClass()
    {
        return $this->class;
    }

    /**
     * @return bool
     */
    public function isPerUnit(): bool
    {
        return $this->per_unit;
    }

    public static function getFixed()
    {
        $fixed = Indicator::all()->whereIn('name', ['Taxa de Ocupação Geral', 'Número de leitos desocupados', 'Número de pacientes internados', 'Número de altas'])->all();
        $re = [];
        foreach ($fixed as $f) {
            $re[] = $f->toArray();
        }
        return $re;
    }

    public static function getToDisplay()
    {
        $fixed = Indicator::all()->all();
        $re = [];
        foreach ($fixed as $f) {
            $re[$f->id] = $f->toArray();
        }
        return $re;
    }

    public
    function toArray()
    {
        $display_type = $this->display_type;

        $array = [
            'id' => $this->id,
            'name' => $this->name,
            'text' => $this->display_name ?: $this->name,
        ];
        if ($display_type !== null) {
            if ($display_type === 'doughnut' || $display_type == 'bar' || $display_type === 'pie') {
                $array['type'] = 'statistic';
                $array['graph'] = $display_type;
            } else {
                $array['type'] = $display_type;
            }
        } else {
            if ($this->isPerUnit()) {
                $array['type'] = 'multiple';
                $array['graph'] = 'none';
            } else {
                $array['type'] = 'numeric';
            }
        }
        if ($this->isPerUnit()) {
            foreach (Unit::getDisplayUnits() as $unit) {
                $array['data'][] = $this->getDisplayLastValue($unit);
                $array['units'][] = $unit->code;

            }
        } else {
            $array['value'] = $this->getDisplayLastValue();
        }
        return $array;
    }

}
