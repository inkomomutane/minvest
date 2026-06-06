<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Neighborhood extends Model
{
    use HasUlids;

    protected $fillable = ['name'];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_name', 'name');
    }
}
