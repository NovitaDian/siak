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
        $glAccounts = [
            ['510310', 'Chemical'],
            ['610100', 'Diesel'],
            ['622105', 'Seasonal Wages'],
            ['624999', 'Other Welfare'],
            ['630101', 'Repai.& Mai Bui.Line'],
            ['630102', 'Rep.&Machine&Equip'],
            ['630105', 'RepairMantMacEqpServ'],
            ['630201', 'Repair&Maint-Buildin'],
            ['630202', 'Rep.&Machine&Equip.'],
            ['630204', 'RepairMantFurOffEqp'],
            ['632100', 'Tool and equipment'],
            ['632101', 'Supplies'],
            ['632102', 'Chemical Supplies'],
            ['635100', 'StdSystmExpnISO18001'],
            ['635200', 'Environment Analysis'],
            ['635201', 'StdSystmExpnISO14000'],
            ['635202', 'StdSystemExpnISO9002'],
            ['635204', 'Project Costs ISO'],
            ['636100', 'Travel-Local'],
            ['636102', 'Public Training'],
            ['636103', 'Inhouse Training'],
            ['636200', 'Communication Expens'],
            ['636304', 'Meeting Expense'],
            ['636305', 'AnnualInhouseFestiva'],
            ['636400', 'Stationary&Printing'],
            ['636505', 'Licenses'],
            ['636602', 'Professional Fee'],
            ['636605', 'Other Fee'],
            ['636903', 'Treating system'],
        ];

        $kategoriList = ['OPEX', 'APEX'];
        $years = ['2023', '2024', '2025'];

        foreach ($glAccounts as [$glCode, $glName]) {
            $internalOrder = rand(0, 1) ? 'IO-' . rand(1000, 9999) : null;
            $year = $years[array_rand($years)];
            $kategori = $kategoriList[array_rand($kategoriList)];
            $bgApprove = rand(10000000, 500000000) / 100;
            $usage = rand(100000, 5000000) / 100;
            $sisa = $bgApprove - $usage;
            $percentageUsage = $bgApprove > 0 ? ($usage / $bgApprove) * 100 : 0;
            $plan = rand(100000, 1000000) / 100;

            // Insert to `budget`
            DB::table('budget')->insert([
                'internal_order' => $internalOrder,
                'gl_code' => $glCode,
                'gl_name' => $glName,
                'setahun_total' => $bgApprove,
                'kategori' => $kategori,
                'year' => $year,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Insert to `budget_fix`
            DB::table('budget_fix')->insert([
                'gl_code' => $glCode,
                'internal_order' => $internalOrder,
                'gl_name' => $glName,
                'bg_approve' => $bgApprove,
                'usage' => $usage,
                'sisa' => $sisa,
                'percentage_usage' => $percentageUsage,
                'kategori' => $kategori,
                'year' => $year,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $this->command->info("Seeder untuk tabel 'budget' dan 'budget_fix' berhasil dijalankan.");
    }
}
