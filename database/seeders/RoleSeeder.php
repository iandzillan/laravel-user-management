<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roles = collect([
            ['name' => 'Super Admin'],
            ['name' => 'Admin'],
            ['name' => 'User'],
        ]);

        foreach ($roles as $role) {
            Role::create([
                'name' => $role['name'],
            ]);
        }
    }
}
