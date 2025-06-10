<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerusahaanTableSeeder extends Seeder
{
    public function run()
    {
        $perusahaans = [
            [
                'perusahaan_name' => 'PT. Dharmapala Usaha Sukses',
                'perusahaan_code' => 26321,
            ],
            [
                'perusahaan_name' => 'PT. RPM',
                'perusahaan_code' => 53270,
            ],
            [
                'perusahaan_name' => 'PT. SBT',
                'perusahaan_code' => 24122,
            ],
            [
                'perusahaan_name' => 'PT. PPT',
                'perusahaan_code' => 61517,
            ],
            [
                'perusahaan_name' => 'PT. Elcander',
                'perusahaan_code' => 32372,
            ],
            [
                'perusahaan_name' => 'PT. Tenang Jaya Sejahtera',
                'perusahaan_code' => 13270,
            ],
            [
                'perusahaan_name' => 'PT. G4S',
                'perusahaan_code' => 24528,
            ],
        ];

        foreach ($perusahaans as $perusahaan) {
            DB::table('perusahaan')->insert([
                'perusahaan_name' => $perusahaan['perusahaan_name'],
                'perusahaan_code' => $perusahaan['perusahaan_code'],
                'city' => '-',
                'street' => '-',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
