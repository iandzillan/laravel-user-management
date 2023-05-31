<?php

namespace Database\Seeders;

use App\Models\Menu;
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
        $modules = [
            [
                'code'        => 'A001',
                'sequence'    => 1,
                'name'        => 'Home',
                'description' => 'Contain dashboard menu'
            ],
            [
                'code'        => 'A002',
                'sequence'    => 2,
                'name'        => 'Settings',
                'description' => 'Contain menus that related to menu and user configuration'
            ]
        ];

        $batch1 = Menu::where('name', 'dashboard')->first();
        $home  = new Modul();
        $home->code         = 'a001';
        $home->sequence     = 1;
        $home->name         = 'home';
        $home->description  = 'contain general menu';
        $home->save();
        $home->menus()->sync($batch1);

        $batch2 = Menu::where('name', '!=', 'dashboard')->get()->pluck('id');
        $setting  = new Modul();
        $setting->code         = 'b001';
        $setting->sequence     = 1;
        $setting->name         = 'setting';
        $setting->description  = 'Contain menus that related to menu and user configuration';
        $setting->save();
        $setting->menus()->sync($batch2);
    }
}
