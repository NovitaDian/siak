<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ToolReport;
use App\Models\Alat;
use App\Models\SentToolReport;
use Faker\Factory as Faker;
use Carbon\Carbon;

class SentToolSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Pastikan ada alat dengan ID tertentu
        $alat = Alat::first();
        if (!$alat) {
            $alat = Alat::create([
                'nama' => 'Alat Demo',
                'status' => 'Aman',
                'waktu_inspeksi' => Carbon::now(),
            ]);
        }

        for ($i = 0; $i < 10; $i++) {
            $tool=SentToolReport::create([
                'user_id' => 1, // pastikan ada user ID 1
                'alat_id' => $alat->id,
                'hse_inspector_id' => 1, // pastikan ada HSE Inspector ID 1
                'tanggal_pemeriksaan' => $faker->dateTimeBetween('-1 month', 'now'),
                'status_pemeriksaan' => $faker->randomElement(['Layak Operasi', 'Layak Operasi Dengan Catatan', 'Tidak Layak Operasi']),
                'foto' => 'default.jpg', // gunakan dummy image path
            ]);
            $alat->update([
                'waktu_inspeksi' => $tool->tanggal_pemeriksaan,
                'status' => $tool->status_pemeriksaan,
            ]);
        }
    }
}
