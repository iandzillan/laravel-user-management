<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Modul;
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

        $employee       = Employee::find(1);
        $modules        = Modul::all()->pluck('id');
        $user           = new User();
        $user->username = 'superadmin';
        $user->email    = strtolower(str_replace(' ', '', $employee->name)) . '@admin.com';
        $user->password = Hash::make('password');
        $user->employee()->associate($employee);
        $user->save();
        $user->modules()->sync($modules);
    }
}
