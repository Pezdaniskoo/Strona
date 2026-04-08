<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Praca', 'color' => '#0d6efd'],
            ['name' => 'Studia', 'color' => '#198754'],
            ['name' => 'Dom', 'color' => '#dc3545'],
            ['name' => 'Zdrowie', 'color' => '#6f42c1'],
            ['name' => 'Zakupy', 'color' => '#fd7e14'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'slug' => Str::slug($category['name']),
                    'color' => $category['color'],
                    'is_active' => true,
                ]
            );
        }
    }
}
