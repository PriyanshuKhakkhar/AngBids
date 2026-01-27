<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'icon' => 'fas fa-laptop',
            ],
            [
                'name' => 'Watches',
                'icon' => 'fas fa-clock',
            ],
            [
                'name' => 'Vintage Cars',
                'icon' => 'fas fa-car',
            ],
            [
                'name' => 'Jewelry',
                'icon' => 'fas fa-gem',
            ],
            [
                'name' => 'Art',
                'icon' => 'fas fa-palette',
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'icon' => $category['icon'],
            ]);
        }
    }
}
