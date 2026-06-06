<?php

namespace App\Models;

use Database\Factories\PropertyRuleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyRule extends Model
{
    /** @use HasFactory<PropertyRuleFactory> */
    use HasFactory;

    protected $fillable = [
        'property_id',
        'category',
        'name',
        'content',
        'icon',
        'icon_type',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

}
