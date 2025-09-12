<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Building;

class BuildingSeeder extends Seeder
{
    public function run(): void
    {
        Building::create([
            'address' => 'г. Томск, ул. Ленина, 1',
            'latitude' => 55.7522200,
            'longitude' => 37.6155600,
        ]);

        Building::create([
            'address' => 'г. Томск, ул. Блюхера, 32/1',
            'latitude' => 55.754093,
            'longitude' => 37.620407,
        ]);

        Building::create([
            'address' => 'г. Томск, ул. Тверская, 10',
            'latitude' => 55.757977,
            'longitude' => 37.613174,
        ]);
    }
}

