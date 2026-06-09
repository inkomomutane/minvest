<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmenityCategory extends Model
{
    protected $table = 'amenity_categories';

    protected $fillable = [
        'name',
        'description',
        'icon',
    ];
}
