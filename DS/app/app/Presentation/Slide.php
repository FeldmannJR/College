<?php

namespace App\Presentation;

use App\Indicators\Indicator;
use App\Presentation\SlideIndicator;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $guarded = [];

    public function indicators()
    {
        return $this->hasMany(SlideIndicator::class);
    }


    /**
     * @param $y
     * @param array $indicators Id dos indicadores
     * @return bool Se foi feito com sucesso a modificao
     */
    public function setIndicators($y, array $indicators)
    {

        $slide_indicators = [];
        $x = 0;
        foreach ($indicators as $indicator) {
            if (!is_object($indicator)) {
                return "Indicador não é objeto!";
            }
            if (!isset($indicator->id)) {
                return "Id do indicador não presente!";
            }
            $slide_indicators[] = SlideIndicator::make(['x' => $x, 'y' => $y, 'indicator_id' => $indicator->id]);
            $x++;
        }
        $this->indicators()->saveMany($slide_indicators);
        return true;
    }

    public static function getPresentation()
    {
        return Slide::orderBy('order', 'asc')->get()->toArray();
    }

    public function toArray()
    {
        $slots = [];
        $indicators = $this->indicators()->orderBy('x', 'asc')->get()->all();

        foreach ($indicators as $indicator) {
            $y = $indicator->y;
            if (!array_key_exists($y, $slots)) {
                $slots[$y] = [];
            }
            $slots[$y][] = $indicator->id;
        }

        return [
            'order' => $this->order,
            'timer' => $this->time,
            'slide' => $slots,
        ];
    }
}
