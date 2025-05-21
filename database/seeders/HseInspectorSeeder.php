<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HseInspectorSeeder extends Seeder
{
    public function run(): void
    {
        $inspectors = [
            'Ali Isdianto',
            'Gandhi Prabowo',
            'Mahwari',
            'Purwono',
            'Sumar Sigit',
            'Sumarno',
            'Teguh Ali M',
            'Triwoko',
            'Wahyu Surya',
        ];

        foreach ($inspectors as $name) {
            DB::table('hse_inspector')->insert([
                'name' => $name,
                'jabatan' => 'Safety Officer',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
