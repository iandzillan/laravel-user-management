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
            'code'        => 'A001',
            'sequence'    => 1,
            'name'        => 'Home',
            'description' => 'Contain dashboard menu'
        ]);

        Modul::create([
            'code'        => 'A002',
            'sequence'    => 2,
            'name'        => 'Settings',
            'description' => 'Contain menus that related to menu and user configuration'
        ]);
    }
}
