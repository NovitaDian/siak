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
            AlatSeeder::class,
            PerusahaanTableSeeder::class,
            BagianPerusahaanSeeder::class,
            GlAccountSeeder::class,
            HseInspectorSeeder::class,
            JumlahHariHilangSeeder::class,
            MaterialGroupSeeder::class,
            NotesTableSeeder::class,
            UnitSeeder::class,
            

        ]);
    }
}
