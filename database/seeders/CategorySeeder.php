<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
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
                'name' => 'Makanan Tradisional',
                'slug' => 'makanan-tradisional',
                'description' => 'Makanan khas daerah dan tradisional Indonesia',
                'icon' => '🍛',
            ],
            [
                'name' => 'Makanan Cepat Saji',
                'slug' => 'makanan-cepat-saji',
                'description' => 'Makanan yang dapat disajikan dengan cepat',
                'icon' => '🍔',
            ],
            [
                'name' => 'Minuman',
                'slug' => 'minuman',
                'description' => 'Berbagai jenis minuman segar dan hangat',
                'icon' => '🥤',
            ],
            [
                'name' => 'Snack & Jajanan',
                'slug' => 'snack-jajanan',
                'description' => 'Makanan ringan dan jajanan tradisional',
                'icon' => '🍡',
            ],
            [
                'name' => 'Kue & Roti',
                'slug' => 'kue-roti',
                'description' => 'Berbagai jenis kue dan roti',
                'icon' => '🍰',
            ],
            [
                'name' => 'Seafood',
                'slug' => 'seafood',
                'description' => 'Makanan laut segar',
                'icon' => '🦐',
            ],
            [
                'name' => 'Ayam & Bebek',
                'slug' => 'ayam-bebek',
                'description' => 'Olahan ayam dan bebek',
                'icon' => '🍗',
            ],
            [
                'name' => 'Sate & Bakar',
                'slug' => 'sate-bakar',
                'description' => 'Makanan sate dan bakar',
                'icon' => '🍖',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
