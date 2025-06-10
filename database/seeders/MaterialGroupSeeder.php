<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialGroupSeeder extends Seeder
{
    public function run()
    {
        $names = [
            'MPSC Supplies',
            'MPSC PDL HW, Purchase Part',
            'MPSC Returnable packaging',
            'MPSC Mat Determination',
            'MPSC Process materials',
            'MPSC Common Mat. - Sugar',
            'MPSC Common Mat. - Energy',
            'MPSC Common Mat. - Prod.+',
            'MPSC Common Mat. -Cassava',
            'MPSC Panel group material',
            'MPSC Spare Parts',
            'MPSC Raw Materials',

        ];

        foreach ($names as $index => $name) {
            DB::table('material_group')->insert([
                'id' => $index + 1,
                'material_group' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
