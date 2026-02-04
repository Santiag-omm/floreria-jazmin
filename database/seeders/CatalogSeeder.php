<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Ramos clásicos',
            'Arreglos modernos',
            'Flores premium',
            'Plantas de interior',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'description' => 'Colección ' . $name, 'is_active' => true]
            );
        }

        $products = [
            ['Ramo Primavera', 'Ramos clásicos', 35.00, 20],
            ['Ramo Minimal', 'Arreglos modernos', 55.00, 12],
            ['Rosas Premium', 'Flores premium', 75.00, 8],
            ['Planta Monstera', 'Plantas de interior', 40.00, 15],
        ];

        foreach ($products as [$name, $categoryName, $price, $stock]) {
            $category = Category::where('name', $categoryName)->first();
            Product::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'category_id' => $category?->id,
                    'name' => $name,
                    'description' => 'Descripción de ' . $name,
                    'price' => $price,
                    'stock' => $stock,
                    'is_active' => true,
                    'is_premium' => $categoryName === 'Flores premium',
                ]
            );
        }

        Promotion::firstOrCreate(
            ['title' => 'San Valentín'],
            [
                'description' => '15% de descuento en arreglos premium',
                'discount_percent' => 15,
                'target_type' => 'premium',
                'starts_at' => now()->subDays(2),
                'ends_at' => now()->addDays(10),
                'is_active' => true,
            ]
        );
    }
}
