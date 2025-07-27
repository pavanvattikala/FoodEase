<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BasicSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        //seed restaurant
        $this->call(RestaurantSeeder::class);
    }
}
