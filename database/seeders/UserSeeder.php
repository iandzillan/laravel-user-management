<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $faker->seed(123);

        for ($i = 0; $i < 1; $i++) {
            $firstname = $faker->firstName();
            $lastname  = $faker->lastName();
            User::create([
                'role_id'  => 1,
                'name'     => $firstname . ' ' . $lastname,
                'email'    => strtolower($firstname) . '@testing.com',
                'username' => strtolower($firstname),
                'password' => Hash::make('password')
            ]);
        }

        for ($i = 0; $i < 1; $i++) {
            $firstname = $faker->firstName();
            $lastname  = $faker->lastName();
            User::create([
                'role_id'  => 2,
                'name'     => $firstname . ' ' . $lastname,
                'email'    => strtolower($firstname) . '@testing.com',
                'username' => strtolower($firstname),
                'password' => Hash::make('password')
            ]);
        }

        for ($i = 0; $i < 3; $i++) {
            $firstname = $faker->firstName();
            $lastname  = $faker->lastName();
            User::create([
                'role_id'  => 3,
                'name'     => $firstname . ' ' . $lastname,
                'email'    => strtolower($firstname) . '@testing.com',
                'username' => strtolower($firstname),
                'password' => Hash::make('password')
            ]);
        }
    }
}
