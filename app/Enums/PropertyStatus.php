<?php

namespace App\Enums;

use App\Concerns\HasEnumToString;
use App\Concerns\HasToObjects;
use App\Concerns\HasToValues;

enum PropertyStatus : string
{
    use HasEnumToString;
    use HasToObjects;
    use HasToValues;

    case Available = 'Available';
    case Unavailable = 'Unavailable';
}
