<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Phone;

class PhoneSeeder extends Seeder
{
    public function run(): void
    {
        // --- 1 уровень ---
        Phone::create([
            'organization_id' => 1,
            'number' => '+7 (495) 111-11-11'
        ]);
        Phone::create([
            'organization_id' => 1,
            'number' => '+7 (495) 111-11-12'
        ]);

        // --- 2 уровень ---
        Phone::create([
            'organization_id' => 2,
            'number' => '+7 (812) 222-22-22'
        ]);
        Phone::create([
            'organization_id' => 4,
            'number' => '+7 (812) 333-33-33'
        ]); // Магазин одежды "Fashion"

        // --- 3 уровень ---
        Phone::create([
            'organization_id' => 3,
            'number' => '+7 (843) 333-33-33'
        ]);
        Phone::create([
            'organization_id' => 7,
            'number' => '+7 (843) 444-44-44'
        ]); // Бутик "Куртки и Ко"

        // --- Автомобили ---
        Phone::create([
            'organization_id' => 8,
            'number' => '+7 (495) 555-55-55'
        ]); // Автосалон "Авто-Глобал"
        Phone::create([
            'organization_id' => 5,
            'number' => '+7 (495) 444-44-44'
        ]); // Грузовые
        Phone::create([
            'organization_id' => 6,
            'number' => '+7 (812) 555-55-55'
        ]); // Легковые
    }
}
