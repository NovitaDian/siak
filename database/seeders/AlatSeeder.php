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
                'nama_alat' => 'Alat Ukur Tekanan',
                'nama_alat_id' => 1,                
                'nomor' => 'ALT-001',
                'waktu_inspeksi' => Carbon::now(),
                'durasi_inspeksi' => 30,
                'status' => 'baik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_alat' => 'Alat Timbang Digital',
                'nama_alat_id' => 1,
                'nomor' => 'ALT-002',
                'waktu_inspeksi' => Carbon::now()->subDays(3),
                'durasi_inspeksi' => 45,
                'status' => 'butuh perbaikan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_alat' => 'Thermometer Digital',
                'nama_alat_id' => 1,
                'nomor' => 'ALT-003',
                'waktu_inspeksi' => Carbon::now()->subDays(7),
                'durasi_inspeksi' => 20,
                'status' => 'rusak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
