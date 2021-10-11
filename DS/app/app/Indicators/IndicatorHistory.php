<?php

namespace App;

use App\Indicators\Indicator;
use Illuminate\Database\Eloquent\Model;

/**
 * App\IndicatorHistory
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IndicatorHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IndicatorHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IndicatorHistory query()
 * @mixin \Eloquent
 */
class IndicatorHistory extends Model
{
    protected $table = "indicators_history";

    protected $guarded = [];

    const UPDATED_AT = null;

    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

}
