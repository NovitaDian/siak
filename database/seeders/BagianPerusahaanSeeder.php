<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class BagianPerusahaanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'code' => 13270,
                'name' => 'PT. Tenang Jaya Sejahtera',
                'bagians' => [
                    'Truck Driver',
                ],
            ],
            [
                'code' => 24122,
                'name' => 'PT. SBT',
                'bagians' => [
                    'Bongkar Raw Sugar / Batubara',
                    'GA/GARDENING',
                    'Daily PWH',
                    'Daily Process',
                    'Daily QCL',
                    'Daily Mechanic',
                    'Daily Power Plant',
                    'Daily MWH',
                    'Operator Forklift',
                    'Project',
                ],
            ],
            [
                'code' => 24528,
                'name' => 'PT. G4S',
                'bagians' => [
                    'Security',
                ],
            ],
            [
                'code' => 26321,
                'name' => 'PT. Dharmapala Usaha Sukses',
                'bagians' => [
                    'Top Management',
                    'Process',
                    'Mechanical',
                    'Electric',
                    'Instrumentation',
                    'Power Plant',
                    'QCL',
                    'HSE',
                    'PWH',
                    'MWH',
                    'Purchasing',
                    'HR&GA',
                    'Finance & Accounting',
                ],
            ],
            [
                'code' => 53270,
                'name' => 'PT. RPM',
                'bagians' => [
                    'Daily GA/Cleaning',
                    'Daily Process',
                    'Daily Power Plant',
                    'Mechanic',
                    'Loading / Stacking',
                    'Operator Forklift',
                    'Operator Loader',
                    'Operator Excavator',
                    'Project',
                ],
            ],
            [
                'code' => 61517,
                'name' => 'PT. PPT',
                'bagians' => [
                    'Kapuran',
                    'ELECTRIC',
                    'HSE',
                    'Project',
                ],
            ],
            [
                'code' => 32372,
                'name' => 'PT. Elcander',
                'bagians' => [
                    'Operator Forklift',
                    'Operator Loader',
                    'Operator Excavator',
                    'Project',
                ],
            ],
        ];

        foreach ($data as $company) {
            foreach ($company['bagians'] as $bagian) {
                DB::table('bagian_perusahaan')->insert([
                    'perusahaan_code' => $company['code'],
                    'perusahaan_name' => $company['name'],
                    'nama_bagian' => $bagian,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
