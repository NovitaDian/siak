<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['unit' => 'EA',  'description' => 'Engineering Analysis'],
            ['unit' => 'KG',  'description' => 'Kilogram'],
            ['unit' => 'PAC', 'description' => 'Physical Access Control'],
            ['unit' => 'ROL', 'description' => 'Return on Investment'],
            ['unit' => 'M',   'description' => 'Meter'],
            ['unit' => 'ST',  'description' => 'Standard Time'],
            ['unit' => 'M2',  'description' => 'Square Meter'],
            ['unit' => 'BT',  'description' => 'British Thermal Unit'],
            ['unit' => 'L',   'description' => 'Liter'],
            ['unit' => 'ML',  'description' => 'Milliliter'],
            ['unit' => 'CAN', 'description' => 'Controller Area Network'],
            ['unit' => 'G',   'description' => 'Gram'],
            ['unit' => 'CAR', 'description' => 'Computer-Aided Reporting'],
            ['unit' => 'M3',  'description' => 'Cubic Meter'],
            ['unit' => 'GAL', 'description' => 'Gallon'],
            ['unit' => 'SET', 'description' => 'Set'],
            ['unit' => 'TBG', 'description' => 'TABUNG'],
            ['unit' => 'PCS', 'description' => 'Pieces'],
        ];

        DB::table('unit')->insert($units);
    }
}
