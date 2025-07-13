<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrSeeder extends Seeder
{
    public function run()
    {


        for ($i = 1; $i <= 10; $i++) {
            $prDate = Carbon::now()->subDays(rand(0, 30));
            $prNo = 'PR-' . str_pad($i, 4, '0', STR_PAD_LEFT);
            $purchaseFor = fake()->company();
            $material = fake()->word();
            $quantity = rand(1, 100);
            $budget = rand(1, 5);
            $unit = rand(1, 5);
            $valuationPrice = rand(10000, 1000000);

            // Insert ke tabel pr
            DB::table('pr')->insert([
                'budget_id' => $budget,
                'unit_id' => $unit,
                'pr_date' => $prDate,
                'pr_no' => $prNo,
                'purchase_for' => $purchaseFor,
                'material' => $material,
                'quantity' => $quantity,
                'valuation_price' => $valuationPrice,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

           
        }
    }
}
