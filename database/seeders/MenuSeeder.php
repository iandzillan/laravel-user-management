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
            'code'       => 'U01',
            'name'       => 'User',
            'icon'       => 'user-cog',
            'route_name' => 'users.index'
        ]);

        Menu::create([
            'code'       => 'U02',
            'name'       => 'Permission',
            'icon'       => 'user-shield',
            'route_name' => 'permissions.index',
        ]);
    }
}
