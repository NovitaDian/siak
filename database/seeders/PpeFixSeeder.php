<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PpeFixSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $entries = [];

        $shifts = ['SHIFT I', 'SHIFT II', 'SHIFT III'];
        $zona = [
            'ZONA I (OFFICE, SECURITY)',
            'ZONA II (PROSES, KAPURAN, CT)',
            'ZONA III (GD SILO, TIMBANGAN, GD BIRU, LAB)',
            'ZONA IV (DEMIN, TURBIN, BOILER)',
            'ZONA V (IPAL, WORKSHOP, MWH)'
        ];
        $lokasi = ['Area Produksi', 'Gudang', 'Workshop', 'Timbangan', 'Lab'];
        $statusNote = ['Patuh', 'Kurang Patuh', 'Perlu Evaluasi'];
        $statusPpe = ['Non-Compliant'];

        for ($month = 1; $month <= 12; $month++) {
            for ($i = 0; $i < 5; $i++) {
                $tanggal = $faker->dateTimeBetween("2024-$month-01", "2024-$month-28");
                $jamMulai = $faker->dateTimeBetween('06:00:00', '08:00:00');
                $jamSelesai = (clone $jamMulai)->modify('+8 hours');

                $entries[] = [
                    'writer' => 'Seeder System',
                    'tanggal_shift_kerja' => $tanggal->format('Y-m-d'),
                    'shift_kerja' => $faker->randomElement($shifts),
                    'hse_inspector_id' => rand(1, 5),
                    'nama_hse_inspector' => 'Inspector ' . rand(1, 5),
                    'jam_mulai' => $jamMulai->format('H:i:s'),
                    'jam_selesai' => $jamSelesai->format('H:i:s'),
                    'zona_pengawasan' => $faker->randomElement($zona),
                    'lokasi_observasi' => $faker->randomElement($lokasi),
                    'jumlah_patuh_apd_karyawan' => rand(5, 15),
                    'jumlah_tidak_patuh_helm_karyawan' => rand(0, 3),
                    'jumlah_tidak_patuh_sepatu_karyawan' => rand(0, 3),
                    'jumlah_tidak_patuh_pelindung_mata_karyawan' => rand(0, 3),
                    'jumlah_tidak_patuh_safety_harness_karyawan' => rand(0, 2),
                    'jumlah_tidak_patuh_apd_lainnya_karyawan' => rand(0, 2),
                    'keterangan_tidak_patuh' => 'Contoh catatan ketidakpatuhan',
                    'jumlah_patuh_apd_kontraktor' => rand(5, 15),
                    'jumlah_tidak_patuh_helm_kontraktor' => rand(0, 3),
                    'jumlah_tidak_patuh_sepatu_kontraktor' => rand(0, 3),
                    'jumlah_tidak_patuh_pelindung_mata_kontraktor' => rand(0, 3),
                    'jumlah_tidak_patuh_safety_harness_kontraktor' => rand(0, 2),
                    'jumlah_tidak_patuh_apd_lainnya_kontraktor' => rand(0, 2),
                    'durasi_ppe' => '08:00',
                    'status_note' => $faker->randomElement($statusNote),
                    'status_ppe' => $faker->randomElement($statusPpe),
                    'status' => 'Nothing',
                    'user_id' => rand(1, 3),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('ppe_fix')->insert($entries);
    }
}
