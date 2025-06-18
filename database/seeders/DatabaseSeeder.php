<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            UserSeeder::class,
            // MaterialGroupSeeder::class,
            // UnitSeeder::class,
            // AlatSeeder::class,
            // HseInspectorSeeder::class,
            // JumlahHariHilangSeeder::class,
            // PerusahaanTableSeeder::class,
            // BagianPerusahaanSeeder::class,
            // GlAccountSeeder::class,
            // BudgetSeeder::class,
            // PrSeeder::class,
            // PpeFixSeeder::class,
            // IncidentFixSeeder::class,
            // NcrFixSeeder::class,
            // PemasukanSeeder::class,
            // PengeluaranSeeder::class,
            
            

        ]);
    }
}
