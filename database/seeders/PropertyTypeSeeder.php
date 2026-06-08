<?php

namespace Database\Seeders;

use App\Models\PropertyType;
use Illuminate\Database\Seeder;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $amenities = [
            'Common',
            'Pool',
            'Gym',
            'Parking',
            'Patio',
            'Balcony',
            'Living room',
            'Dining room',
            'Kitchen',
            'Bedroom',
            'Bathroom',
            'Garage',
            'Laundry room',
        ];
        foreach ($amenities as $amenity) {
            PropertyType::updateOrCreate([
                'name' => $amenity,
            ], [
                'name' => $amenity,
            ]);
        }
    }
}
