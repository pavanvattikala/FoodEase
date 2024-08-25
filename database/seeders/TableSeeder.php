<?php

namespace Database\Seeders;

use App\Models\Table;
use App\Models\TableLocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $tableLocations = [
            ['name' => 'front'],
            ['name' => 'inside'],
        ];

        foreach ($tableLocations as $tableLocation) {
            TableLocation::create($tableLocation);
        }

        $tables = [
            ['name' => 'G1', 'guest_number' => 4, 'status' => 'available', 'table_location' => 1],
            ['name' => 'G2', 'guest_number' => 4, 'status' => 'available', 'table_location' => 1],
            ['name' => 'G3', 'guest_number' => 4, 'status' => 'available', 'table_location' => 1],
            ['name' => 'A1', 'guest_number' => 4, 'status' => 'available', 'table_location' => 2],
            ['name' => 'G4', 'guest_number' => 4, 'status' => 'available', 'table_location' => 1],
            ['name' => 'G5', 'guest_number' => 4, 'status' => 'available', 'table_location' => 1],
        ];

        foreach ($tables as $table) {
            Table::create($table);
        }
    }
}
