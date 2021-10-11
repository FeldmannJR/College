<?php

namespace App\Indicators;

use Illuminate\Database\Eloquent\Model;

class IndicatorQuery extends Model
{
    protected $guarded = [];


    public function indicatorQuery()
    {
        return $this->hasOne(Indicator::class, "id", "id");
    }

    public function usesTimestamps()
    {
        return false;
    }
}
