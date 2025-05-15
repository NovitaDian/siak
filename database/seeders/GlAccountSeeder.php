<?php

namespace Database\Seeders;

use App\Models\Gl_Account;
use Illuminate\Database\Seeder;

class GlAccountSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['gl_code' => '510310', 'gl_name' => 'Chemical'],
            ['gl_code' => '610100', 'gl_name' => 'Diesel'],
            ['gl_code' => '622105', 'gl_name' => 'Seasonal Wages'],
            ['gl_code' => '624999', 'gl_name' => 'Other Welfare'],
            ['gl_code' => '630101', 'gl_name' => 'Repai.& Mai Bui.Line'],
            ['gl_code' => '630102', 'gl_name' => 'Rep.&Machine&Equip'],
            ['gl_code' => '630105', 'gl_name' => 'RepairMantMacEqpServ'],
            ['gl_code' => '630201', 'gl_name' => 'Repair&Maint-Buildin'],
            ['gl_code' => '630202', 'gl_name' => 'Rep.&Machine&Equip.'],
            ['gl_code' => '630204', 'gl_name' => 'RepairMantFurOffEqp'],
            ['gl_code' => '632100', 'gl_name' => 'Tool and equipment'],
            ['gl_code' => '632101', 'gl_name' => 'Supplies'],
            ['gl_code' => '632102', 'gl_name' => 'Chemical Supplies'],
            ['gl_code' => '635100', 'gl_name' => 'StdSystmExpnISO18001'],
            ['gl_code' => '635200', 'gl_name' => 'Environment Analysis'],
            ['gl_code' => '635201', 'gl_name' => 'StdSystmExpnISO14000'],
            ['gl_code' => '635202', 'gl_name' => 'StdSystemExpnISO9002'],
            ['gl_code' => '635204', 'gl_name' => 'Project Costs ISO'],
            ['gl_code' => '636100', 'gl_name' => 'Travel-Local'],
            ['gl_code' => '636102', 'gl_name' => 'Public Training'],
            ['gl_code' => '636103', 'gl_name' => 'Inhouse Training'],
            ['gl_code' => '636200', 'gl_name' => 'Communication Expens'],
            ['gl_code' => '636304', 'gl_name' => 'Meeting Expense'],
            ['gl_code' => '636305', 'gl_name' => 'AnnualInhouseFestiva'],
            ['gl_code' => '636400', 'gl_name' => 'Stationary&Printing'],
            ['gl_code' => '636505', 'gl_name' => 'Licenses'],
            ['gl_code' => '636602', 'gl_name' => 'Professional Fee'],
            ['gl_code' => '636605', 'gl_name' => 'Other Fee'],
            ['gl_code' => '636903', 'gl_name' => 'Treating system'],
        ];

        foreach ($data as $item) {
            Gl_Account::updateOrCreate(
                ['gl_code' => $item['gl_code']],
                ['gl_name' => $item['gl_name']]
            );
        }
    }
}
