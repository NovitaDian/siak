<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NamaAlatSeeder extends Seeder
{
    public function run()
    {
        DB::table('nama_alats')->insert([
            [
                'id' => 1,
                'nama_alat' => 'Forklift',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nama_alat' => 'Dinamo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nama_alat' => 'Viar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
                        
        ]);
    }
}
