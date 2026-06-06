<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Amenity extends Model
{
    use HasUlids;


    protected $fillable = [
        'name',
        'description',
        'category',
        'icon',
        'icon_type',
    ];

    public function categoryRelation(): BelongsTo
    {
        return $this->belongsTo(AmenityCategory::class, 'category', 'name');
    }
}
