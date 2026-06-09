<?php

namespace App\Enums;

use App\Concerns\HasEnumToString;
use App\Concerns\HasToObjects;
use App\Concerns\HasToValues;

enum UserRole : string
{

    use HasEnumToString;
    use HasToObjects;
    use HasToValues;

    case SuperAdmin = 'super-admin';
    case Admin = 'admin';
    case Manager = 'manager';
    case Guest = 'guest';
}
