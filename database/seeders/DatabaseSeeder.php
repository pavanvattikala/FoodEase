<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //seed users
        $this->call(UserSeeder::class);
        //seed restaurant
        $this->call(RestaurantSeeder::class);
        //seed menus
        $this->call(MenuSeeder::class);
        //seed tables
        $this->call(TableSeeder::class);
    }
}
