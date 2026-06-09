<?php

namespace App\Models;

use Database\Factories\PropertyAddressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyAddress extends Model
{
    /** @use HasFactory<PropertyAddressFactory> */
    use HasFactory;

    protected $fillable = [
        'property_id',
        'street',
        'city',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'directions',
        'google_maps_embed_code',
        'google_map_link',
        'google_maps_place_id',
    ];
}
