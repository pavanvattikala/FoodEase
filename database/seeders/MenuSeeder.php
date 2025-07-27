<?php

namespace Database\Seeders;

use App\Enums\MenuType;
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
                ['name' => 'specials', 'rank' => 1],
                ['name' => 'veg', 'rank' => 2],
                ['name' => 'non-veg', 'rank' => 3],
                ['name' => 'beverages', 'rank' => 4]
            ];

            foreach ($categories as $category) {
                Category::create($category);
            }

            // Create Menu Items
            $specialsMenuItems = [
                ['name' => 'Mutton Curry', 'shortcode' => 'mc', 'price' => 250.00, 'type' => MenuType::Service],
                ['name' => 'Chicken Curry', 'shortcode' => 'cc', 'price' => 140.00, 'type' => MenuType::Service],
            ];
            $nonVegMenuItems = [
                ['name' => 'CB Single', 'shortcode' => 'cbs', 'price' => 130.00, 'type' => MenuType::Service],
                ['name' => 'CB Full', 'shortcode' => 'cbf',  'price' => 250.00, 'type' => MenuType::Service],
            ];
            $vegMenuItems = [
                ['name' => 'Veg Curry', 'shortcode' => 'vc', 'price' => 150.00, 'type' => MenuType::Service],
                ['name' => 'Veg Meals', 'shortcode' => 'vm', 'price' => 200.00, 'type' => MenuType::Service],
            ];

            $beveragesMenuItems = [
                ['name' => 'Water Bottle', 'shortcode' => 'b', 'price' => 20.00, 'type' => MenuType::Stock],
                ['name' => 'Cool Drink 250ml', 'shortcode' => 'c', 'price' => 20.00, 'type' => MenuType::Stock]
            ];

            foreach ($specialsMenuItems as $menuItem) {
                $menu = Menu::create([
                    'name' => $menuItem['name'],
                    'shortcode' => $menuItem['shortcode'],
                    'price' => $menuItem['price'],
                    'type' => MenuType::Service
                ]);
                $menu->category()->attach(1);
            }
            foreach ($vegMenuItems as $menuItem) {
                $menu = Menu::create([
                    'name' => $menuItem['name'],
                    'shortcode' => $menuItem['shortcode'],
                    'price' => $menuItem['price'],
                    'type' => MenuType::Service

                ]);
                $menu->category()->attach(2);
            }
            foreach ($nonVegMenuItems as $menuItem) {
                $menu = Menu::create([
                    'name' => $menuItem['name'],
                    'shortcode' => $menuItem['shortcode'],
                    'price' => $menuItem['price'],
                    'type' => MenuType::Service

                ]);
                $menu->category()->attach(3);
            }

            foreach ($beveragesMenuItems as $menuItem) {
                $menu = Menu::create([
                    'name' => $menuItem['name'],
                    'shortcode' => $menuItem['shortcode'],
                    'price' => $menuItem['price'],
                    'type' => MenuType::Stock,
                    'quantity' => 50,
                ]);
                $menu->category()->attach(4);
            }
        }
    }
}
