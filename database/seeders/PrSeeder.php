<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrSeeder extends Seeder
{
    public function run()
    {
        $glList = [
            ['gl_code' => '510310', 'gl_name' => 'Chemical'],
            ['gl_code' => '610100', 'gl_name' => 'Diesel'],
            ['gl_code' => '622105', 'gl_name' => 'Seasonal Wages'],
            ['gl_code' => '624999', 'gl_name' => 'Other Welfare'],
            ['gl_code' => '630101', 'gl_name' => 'Repair & Maint. Building Line'],
        ];

        for ($i = 1; $i <= 10; $i++) {
            $gl = $glList[array_rand($glList)];
            $prDate = Carbon::now()->subDays(rand(0, 30));
            $prNo = 'PR-' . str_pad($i, 4, '0', STR_PAD_LEFT);
            $purchaseFor = fake()->company();
            $ioAssetcode = rand(0, 1) ? 'IO-' . rand(1000, 9999) : null;
            $material = fake()->word();
            $quantity = rand(1, 100);
            $unit = fake()->randomElement(['PCS', 'L', 'KG', 'BOX']);
            $valuationPrice = rand(10000, 1000000);
            $description = fake()->sentence();

            // Insert ke tabel pr
            DB::table('pr')->insert([
                'pr_date' => $prDate,
                'pr_no' => $prNo,
                'purchase_for' => $purchaseFor,
                'io_assetcode' => $ioAssetcode,
                'material' => $material,
                'quantity' => $quantity,
                'unit' => $unit,
                'valuation_price' => $valuationPrice,
                'gl_code' => $gl['gl_code'],
                'gl_name' => $gl['gl_name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Cari budget_fix terbaru untuk gl_code tersebut
            $budgetFix = DB::table('budget_fix')
                ->where('gl_code', $gl['gl_code'])
                ->latest('created_at')
                ->first();

            if ($budgetFix) {
                $usage = $valuationPrice;
                $bgApprove = $budgetFix->bg_approve ?? 0;
                $internalOrder = $ioAssetcode;
                $sisaUsage = $budgetFix->sisa - $usage;
                $percentageUsage = $bgApprove > 0 ? ($usage / $bgApprove) * 100 : 0;

                if ($budgetFix->usage > 0) {
                    // Jika sudah ada usage sebelumnya, buat baris baru di budget_fix
                    DB::table('budget_fix')->insert([
                        'gl_code' => $gl['gl_code'],
                        'usage' => $usage,
                        'internal_order' => $internalOrder,
                        'gl_name' => $gl['gl_name'],
                        'percentage_usage' => $percentageUsage,
                        'sisa' => $sisaUsage,
                        'bg_approve' => $budgetFix->bg_approve,
                        'kategori' => $budgetFix->kategori,
                        'year' => $budgetFix->year,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Update sisa pada record budget_fix sebelumnya
                    DB::table('budget_fix')
                        ->where('id', $budgetFix->id)
                        ->update(['sisa' => $sisaUsage]);
                } else {
                    // Jika usage belum pernah diisi, update baris budget_fix yang ada
                    DB::table('budget_fix')
                        ->where('id', $budgetFix->id)
                        ->update([
                            'usage' => $usage,
                            'internal_order' => $internalOrder,
                            'percentage_usage' => $percentageUsage,
                            'sisa' => $sisaUsage,
                            'updated_at' => now(),
                        ]);
                }
            }
        }
    }
}
