<?php

namespace Database\Seeders;

use App\Models\EmployeeCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $password = Hash::make(env('DEFAULT_USER_PASSWORD'));

        EmployeeCategory::create([
            'name' => 'admin'
        ]);
        EmployeeCategory::create([
            'name' => 'waiter'
        ]);
        EmployeeCategory::create([
            'name' => 'kitchen'
        ]);
        EmployeeCategory::create([
            'name' => 'biller'
        ]);



        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'pin' => '1',
            'email_verified_at' => now(),
            'password' => $password,
            'remember_token' => Str::random(10),
            'category_id' => 1
        ]);
        User::create([
            'name' => 'Waiter',
            'email' => 'waiter@gmail.com',
            'pin' => '2',
            'email_verified_at' => now(),
            'password' => $password,
            'remember_token' => Str::random(10),
            'category_id' => 2
        ]);
        User::create([
            'name' => 'Kitchen',
            'email' => 'kitchen@gmail.com',
            'pin' => '3',
            'email_verified_at' => now(),
            'password' => $password,
            'remember_token' => Str::random(10),
            'category_id' => 3
        ]);
        User::create([
            'name' => 'Biller',
            'email' => 'biller@gmail.com',
            'pin' => '4',
            'email_verified_at' => now(),
            'password' => $password,
            'remember_token' => Str::random(10),
            'category_id' => 4
        ]);
    }
}
