<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AlatSeeder extends Seeder
{
    public function run()
    {
        DB::table('alats')->insert([
            [
                'nama_alat_id' => 1,                
                'nomor' => 'ALT-001',
                'durasi_inspeksi' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_alat_id' => 1,
                'nomor' => 'ALT-002',
                'durasi_inspeksi' => 45,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_alat_id' => 1,
                'nomor' => 'ALT-003',
                'durasi_inspeksi' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
