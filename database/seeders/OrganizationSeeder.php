<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        // --- 1 уровень ---
        $org1 = Organization::create([
            'name' => 'Супермаркет "Универсал"',
            'building_id' => 1,
        ]);
        $org1->activities()->attach([1]); // Магазин (1 уровень)

        // --- 2 уровень ---
        $org2 = Organization::create([
            'name' => 'Продуктовый магазин "Вкусняшка"',
            'building_id' => 2,
        ]);
        $org2->activities()->attach([2]); // Продукты (2 уровень)

        // --- 3 уровень ---
        $org3 = Organization::create([
            'name' => 'Молочный киоск "Ферма"',
            'building_id' => 3,
        ]);
        $org3->activities()->attach([4]); // Молочка (3 уровень)

        $org4 = Organization::create([
            'name' => 'Магазин одежды "Fashion"',
            'building_id' => 2,
        ]);
        $org4->activities()->attach([3]); // Одежда (2 уровень)

        $org7 = Organization::create([
            'name' => 'Бутик "Куртки и Ко"',
            'building_id' => 3,
        ]);
        $org7->activities()->attach([5]); // Куртки (3 уровень)

        $org8 = Organization::create([
            'name' => 'Автосалон "Авто-Глобал"',
            'building_id' => 3,
        ]);
        $org8->activities()->attach([5]); // Автомобили (1 уровень)

        $org5 = Organization::create([
            'name' => 'Автосалон "Авто-Мир"',
            'building_id' => 1,
        ]);
        $org5->activities()->attach([6]); // Грузовые (2 уровень)

        $org6 = Organization::create([
            'name' => 'Автосалон "Легковушки"',
            'building_id' => 2,
        ]);
        $org6->activities()->attach([7]); // Легковые (2 уровень)

    }
}
