<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::create([
            'code'       => 'A01',
            'sequence'   => 1,
            'name'       => 'Dashboard',
            'icon'       => 'layout-dashboard',
            'route_name' => 'dashboard.index'
        ]);

        Menu::create([
            'code'       => 'B01',
            'sequence'   => 2,
            'name'       => 'Permission',
            'icon'       => 'fingerprint',
            'route_name' => 'permissions.index',
        ]);

        Menu::create([
            'code'       => 'C01',
            'sequence'   => 3,
            'name'       => 'Menu',
            'icon'       => 'folders',
            'route_name' => 'menus.index',
        ]);

        Menu::create([
            'code'       => 'D01',
            'sequence'   => 4,
            'name'       => 'Modul',
            'icon'       => 'folder',
            'route_name' => 'modules.index',
        ]);

        Menu::create([
            'code'       => 'E01',
            'sequence'   => 5,
            'name'       => 'User',
            'icon'       => 'user-cog',
            'route_name' => 'users.index',
        ]);
    }
}
