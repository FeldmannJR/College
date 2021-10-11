<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UpdateType extends Enum
{
    const RealTime = 0;
    const Daily = 1;
    const Weekly = 2;
    const Monthly = 3;
}
