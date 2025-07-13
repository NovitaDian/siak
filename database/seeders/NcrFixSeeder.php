<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NcrFixSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');

        for ($i = 1; $i <= 20; $i++) {
            DB::table('ncr_fix')->insert([
                'user_id' => rand(1, 3),
                'tanggal_shift_kerja' => $faker->date(),
                'shift_kerja' => $faker->randomElement(['Shift 1', 'Shift 2', 'Shift 3']),
                'nama_hs_officer_1' => $faker->optional()->name,
                'nama_hs_officer_2' => $faker->optional()->name,
                'tanggal_audit' => $faker->date(),
                'nama_auditee' => $faker->optional()->name,
                'perusahaan_id' => $faker->randomElement(['1', '2']),
                'nama_bagian' => $faker->randomElement(['Contractor', 'Process']),
                'element_referensi_ncr' => $faker->sentence,
                'kategori_ketidaksesuaian' => $faker->randomElement(['System Documentation', 'Implementation/Pratices', 'Review/Analysis', 'Improvement Action']),
                'deskripsi_ketidaksesuaian' => $faker->paragraph,
                'status' => $faker->randomElement(['Nothing']),
                'status_note' => $faker->optional()->sentence,
                'status_ncr' => $faker->randomElement(['Open', 'Closed']),
                'estimasi' => $faker->optional()->date(),
                'tindak_lanjut' => $faker->optional()->paragraph,
                'foto' => null,
                'foto_closed' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $this->command->info("Seeder ncr_fix berhasil diisi dengan dummy data.");
    }
}
