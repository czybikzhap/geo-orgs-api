<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        // --- Магазины с 3 уровнями ---
        $shop = Activity::create(['name' => 'Магазин']); // 1 уровень

        $food = Activity::create([
            'name' => 'Продукты',
            'parent_id' => $shop->id
        ]); // 2 уровень

        $cloth = Activity::create([
            'name' => 'Одежда',
            'parent_id' => $shop->id
        ]); // 2 уровень

        Activity::create([
            'name' => 'Молочка',
            'parent_id' => $food->id
        ]); // 3 уровень

        Activity::create([
            'name' => 'Куртки',
            'parent_id' => $cloth->id
        ]); // 3 уровень


        // --- Автомобили с 2 уровнями ---
        $auto = Activity::create(['name' => 'Автомобили']); // 1 уровень

        Activity::create([
            'name' => 'Грузовые',
            'parent_id' => $auto->id
        ]); // 2 уровень

        Activity::create([
            'name' => 'Легковые',
            'parent_id' => $auto->id
        ]); // 2 уровень
    }
}
