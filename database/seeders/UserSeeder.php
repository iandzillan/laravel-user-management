<?php

namespace Database\Seeders;

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

        for ($i = 0; $i < 5; $i++) {
            $firstname = $faker->firstName();
            $lastname  = $faker->lastName();
            User::create([
                'name'     => $firstname . ' ' . $lastname,
                'email'    => strtolower($firstname) . '@example.com',
                'username' => strtolower($firstname),
                'password' => Hash::make('password')
            ]);
        }
    }
}
