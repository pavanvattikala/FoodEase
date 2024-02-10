<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //public function run()
        {
            // Create Categories
            $categories = [
                ['name' => 'specials'],
                ['name' => 'veg'],
                ['name' => 'non-veg'],
            ];

            foreach ($categories as $category) {
                Category::create($category);
            }

            // Create Menu Items
            $specialsMenuItems = [
                ['name' => 'Mutton Curry', 'shortcode' => 'mc', 'price' => 250.00],
                ['name' => 'Chicken Curry', 'shortcode' => 'cc', 'price' => 140.00],
            ];
            $nonVegMenuItems = [
                ['name' => 'CB Single', 'shortcode' => 'cbs', 'price' => 130.00],
                ['name' => 'CB Full', 'shortcode' => 'cbf',  'price' => 250.00],
            ];
            $vegMenuItems = [
                ['name' => 'Veg Curry', 'shortcode' => 'vc', 'price' => 150.00],
                ['name' => 'Veg Meals', 'shortcode' => 'vm', 'price' => 200.00],
            ];

            foreach ($specialsMenuItems as $menuItem) {
                Menu::create([
                    'name' => $menuItem['name'],
                    'price' => $menuItem['price'],
                    'category_id' => 1,
                ]);
            }
            foreach ($vegMenuItems as $menuItem) {
                Menu::create([
                    'name' => $menuItem['name'],
                    'price' => $menuItem['price'],
                    'category_id' => 2,
                ]);
            }
            foreach ($nonVegMenuItems as $menuItem) {
                Menu::create([
                    'name' => $menuItem['name'],
                    'price' => $menuItem['price'],
                    'category_id' => 3,
                ]);
            }
        }
    }
}
