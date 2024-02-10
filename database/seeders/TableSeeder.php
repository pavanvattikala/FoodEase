<?php

namespace Database\Seeders;

use App\Models\Table;
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
        $tables = [
            ['name' => 'G1', 'guest_number' => 4, 'status' => 'available', 'location' => 'front'],
            ['name' => 'G2', 'guest_number' => 4, 'status' => 'available', 'location' => 'front'],
            ['name' => 'G3', 'guest_number' => 4, 'status' => 'available', 'location' => 'front'],
            ['name' => 'A1', 'guest_number' => 4, 'status' => 'available', 'location' => 'inside'],
            ['name' => 'G4', 'guest_number' => 4, 'status' => 'available', 'location' => 'front'],
            ['name' => 'G5', 'guest_number' => 4, 'status' => 'available', 'location' => 'front'],
        ];

        foreach ($tables as $table) {
            Table::create($table);
        }
    }
}
