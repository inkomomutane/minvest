<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Role extends \Spatie\Permission\Models\Role
{
    use HasUlids;
}
