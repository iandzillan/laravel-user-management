<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $faker->seed(123);

        for ($i = 0; $i < 2; $i++) {
            Employee::create([
                'name' => $faker->firstName() . ' ' . $faker->lastName(),
                'dept' => 'IT Department'
            ]);
        }

        for ($i = 0; $i < 2; $i++) {
            Employee::create([
                'name' => $faker->firstName() . ' ' . $faker->lastName(),
                'dept' => 'HR Department'
            ]);
        }
    }
}
