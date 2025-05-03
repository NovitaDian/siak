<?php

namespace Database\Seeders;

use App\Models\Perusahaan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PerusahaanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Perusahaan::insert([

            [
                'id' => 1,
                'perusahaan_code' => '115568',
                'perusahaan_name' => 'PT. OLAM  INDONESIA',
                'city' => 'Jakarta-DKI',
                'street' => 'Gandaria 8 Office Building Lt.15',
                'created_at' => '2025-02-26 06:56:22',
                'updated_at' => '2025-02-26 06:56:22',
            ],
            [
                'id' => 1151,
                'perusahaan_code' => '00013',
                'perusahaan_name' => 'PT.SATU DUA',
                'city' => 'Jakarta-DKI',
                'street' => 'Jalan Laut Kompleks Pelabuhan Tanjung Intan, Klega...',
                'created_at' => '2025-02-26 06:57:37',
                'updated_at' => '2025-02-26 06:57:37',
            ],
        ]);
    }
}
