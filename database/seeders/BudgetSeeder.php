<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BudgetSeeder extends Seeder
{
    public function run()
    {


        $kategoriList = ['OPEX', 'CAPEX'];
        $years = ['2023', '2024', '2025'];

        for ($i = 1; $i <= 10; $i++) {
            $internalOrder = rand(0, 1) ? 'IO-' . rand(1000, 9999) : null;
            $glId = rand(1, 5);
            $year = $years[array_rand($years)];
            $kategori = $kategoriList[array_rand($kategoriList)];
            $bgApprove = rand(10000000, 500000000) / 100;

            // Insert to `budget`
            DB::table('budget')->insert([
                'internal_order' => $internalOrder,
                'gl_id' => $glId,
                'setahun_total' => $bgApprove,
                'kategori' => $kategori,
                'year' => $year,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }



        $this->command->info("Seeder untuk tabel 'budget' dan 'budget_fix' berhasil dijalankan.");
    }
}
