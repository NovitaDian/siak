<?php

namespace Database\Seeders;

use App\Models\Note;
use Illuminate\Database\Seeder;

class NotesTableSeeder extends Seeder
{
    public function run(): void
    {
        Note::insert([
            [
                'user_id' => 1,
                'note' => 'Tindakan tidak aman adalah ketika seseorang yang melakukan pekerjaan tidak mematuhi prosedur atau standar keselamatan kerja.',
                'created_at' => '2025-02-26 06:56:22',
                'updated_at' => '2025-02-26 06:56:22',
            ],
            [
                'user_id' => 1,
                'note' => 'Perilaku tidak aman didefinisikan sebagai perilaku yang dapat menyebabkan kecelakaan kerja seperti tidak memakai APD.',
                'created_at' => '2025-02-26 06:57:37',
                'updated_at' => '2025-02-26 06:57:37',
            ],
        ]);
    }
}
