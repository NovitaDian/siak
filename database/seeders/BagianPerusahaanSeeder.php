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
                'perusahaan_id' => 1,
                'bagians' => [
                    'Truck Driver',
                ],
            ],
            [
                'perusahaan_id' => 2,
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
                'perusahaan_id' => 3,
                'bagians' => [
                    'Security',
                ],
            ],
            [
                'perusahaan_id' => 4,
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
                'perusahaan_id' => 5,
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
                'perusahaan_id' => 6,
                'bagians' => [
                    'Kapuran',
                    'ELECTRIC',
                    'HSE',
                    'Project',
                ],
            ],
            [
                'perusahaan_id' => 7,
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
                    'perusahaan_id' => $company['perusahaan_id'],
                    'nama_bagian' => $bagian,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
