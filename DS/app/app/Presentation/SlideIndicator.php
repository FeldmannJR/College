<?php

namespace App\Presentation;

use App\Indicators\Indicator;
use App\Presentation\Slide;
use Illuminate\Database\Eloquent\Model;

class SlideIndicator extends Model
{
    protected $guarded = [];

    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }

    public function slide()
    {
        return $this->belongsTo(Slide::class);
    }
}
