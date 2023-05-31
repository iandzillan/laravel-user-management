<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'code'       => 'A01',
                'sequence'   => 1,
                'name'       => 'dashboard',
                'icon'       => 'layout-dashboard',
                'route_name' => 'dashboard.index'
            ],
            [
                'code'       => 'B01',
                'sequence'   => 2,
                'name'       => 'permission',
                'icon'       => 'fingerprint',
                'route_name' => 'permissions.index'
            ],
            [
                'code'       => 'C01',
                'sequence'   => 3,
                'name'       => 'menu',
                'icon'       => 'folders',
                'route_name' => 'menus.index'
            ],
            [
                'code'       => 'D01',
                'sequence'   => 4,
                'name'       => 'modul',
                'icon'       => 'folder',
                'route_name' => 'modules.index'
            ],
            [
                'code'       => 'E01',
                'sequence'   => 5,
                'name'       => 'user',
                'icon'       => 'user-cog',
                'route_name' => 'users.index'
            ]
        ];

        $permissions = Permission::find([1, 2, 3, 4, 5]);
        foreach ($menus as $item) {
            $menu = new Menu();
            $menu->code       = $item['code'];
            $menu->sequence   = $item['sequence'];
            $menu->name       = $item['name'];
            $menu->route_name = $item['route_name'];
            $menu->icon       = $item['icon'];
            $menu->save();
            $menu->permissions()->sync($permissions);
        }
    }
}
