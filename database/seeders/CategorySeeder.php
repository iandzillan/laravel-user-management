<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'code' => 'CA01',
                'name' => 'Computer Accessories',
            ],
            [
                'code' => 'PC01',
                'name' => 'PC'
            ],
            [
                'code' => 'SE01',
                'name' => 'Server'
            ],
            [
                'code' => 'SO01',
                'name' => 'Software'
            ]
        ];

        foreach ($categories as $category) {
            Category::create([
                'code' => $category['code'],
                'name' => $category['name']
            ]);
        }
    }
}
