<?php

namespace Database\Seeders;

use App\Models\Modul;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Modul::create([
            'code'        => 'U001',
            'name'        => 'User Modul',
            'description' => 'Contain menus that related to user data'
        ]);
    }
}
