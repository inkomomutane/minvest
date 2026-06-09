<?php

namespace App\Models;

use App\Enums\PropertyStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasUlids;

    protected $fillable = [
        'name',
        'about',
        'type',
        'status',
        'slug',
        'bedrooms',
        'beds',
        'bathrooms',
        'max_guests',
        'max_kids_allowed',
        'max_pets_allowed',
        'max_vehicles_allowed',
        'area_size',
        'base_price',
        'cleaning_fee',
        'service_fee',
        'taxes',
        'security_deposit',
        'extra_guest_fee',
        'pet_fee',
        'vehicle_fee',
    ];

    protected function casts(): array
    {
        return [
            'status' => PropertyStatus::class,
        ];
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'property_id');
    }

    public function getAttributeRatingAvg()
    {
        return $this->ratings()->avg('ratings');
    }
}
