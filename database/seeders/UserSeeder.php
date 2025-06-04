<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
            'id' => 1,
            'name' => 'admin',
            'role' => 'adminsystem',
            'email' => 'admin@softui.com',
            'password' => Hash::make('secret'),
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'id' => 2,
            'name' => 'operator',
            'role' => 'operator',
            'email' => 'operator@softui.com',
            'password' => Hash::make('secret'),
            'created_at' => now(),
            'updated_at' => now()
        ]
        ];
        DB::table('users')->insert($users);
  
    }
}
