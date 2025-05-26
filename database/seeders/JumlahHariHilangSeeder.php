<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JumlahHariHilangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ["jenis_luka" => "Amputasi seluruh atau sebagian tangan dan jari-jari dari tulang Ibu Jari bagian ruas ujung", "jml_hari_hilang" => 300],
            ["jenis_luka" => "Amputasi seluruh atau sebagian tangan dan jari-jari dari tulang Ibu Jari bagian ruas pangkal", "jml_hari_hilang" => 600],
            ["jenis_luka" => "Amputasi seluruh atau sebagian tangan dan jari-jari dari tulang Jari Telunjuk bagian ruas ujung", "jml_hari_hilang" => 100],
            ["jenis_luka" => "Amputasi seluruh atau sebagian tangan dan jari-jari dari tulang Jari Telunjuk bagian ruas tengah", "jml_hari_hilang" => 200],
            ["jenis_luka" => "Amputasi seluruh atau sebagian tangan dan jari-jari dari tulang Jari Telunjuk bagian ruas pangkal", "jml_hari_hilang" => 400],
            ["jenis_luka" => "Amputasi seluruh atau sebagian tangan dan jari-jari dari tulang Jari Tengah bagian ruas ujung", "jml_hari_hilang" => 75],
            ["jenis_luka" => "Amputasi seluruh atau sebagian tangan dan jari-jari dari tulang Jari Tengah bagian ruas tengah", "jml_hari_hilang" => 150],
            ["jenis_luka" => "Amputasi seluruh atau sebagian tangan dan jari-jari dari tulang Jari Tengah bagian ruas pangkal", "jml_hari_hilang" => 300],
            ["jenis_luka" => "Amputasi seluruh atau sebagian tangan dan jari-jari dari tulang Jari Manis bagian ruas ujung", "jml_hari_hilang" => 60],
            ["jenis_luka" => "Amputasi seluruh atau sebagian tangan dan jari-jari dari tulang Jari Manis bagian ruas tengah", "jml_hari_hilang" => 120],
            ["jenis_luka" => "Amputasi seluruh atau sebagian tangan dan jari-jari dari tulang Jari Manis bagian ruas pangkal", "jml_hari_hilang" => 240],
            ["jenis_luka" => "Amputasi seluruh atau sebagian tangan dan jari-jari dari tulang Jari Kelingking bagian ruas ujung", "jml_hari_hilang" => 50],
            ["jenis_luka" => "Amputasi seluruh atau sebagian tangan dan jari-jari dari tulang Jari Kelingking bagian ruas tengah", "jml_hari_hilang" => 100],
            ["jenis_luka" => "Amputasi seluruh atau sebagian tangan dan jari-jari dari tulang Jari Kelingking bagian ruas pangkal", "jml_hari_hilang" => 200],
            ["jenis_luka" => "Amputasi seluruh atau sebagian tangan dan jari-jari dari tulang Telapak antara jari- jari dan pergelangan bagian Ibu Jari", "jml_hari_hilang" => 900],
            ["jenis_luka" => "Amputasi seluruh atau sebagian tangan dan jari-jari dari tulang Telapak antara jari- jari dan pergelangan bagian Jari Telunjuk", "jml_hari_hilang" => 600],
            ["jenis_luka" => "Amputasi seluruh atau sebagian tangan dan jari-jari dari tulang Telapak antara jari- jari dan pergelangan bagian Jari Tengah", "jml_hari_hilang" => 500],
            ["jenis_luka" => "Amputasi seluruh atau sebagian tangan dan jari-jari dari tulang Telapak antara jari- jari dan pergelangan bagian Jari Manis", "jml_hari_hilang" => 450],
            ["jenis_luka" => "Amputasi seluruh atau sebagian tangan dan jari-jari dari tulang Tangan sampai pergelangan", "jml_hari_hilang" => 3000],
            ["jenis_luka" => "Amputasi seluruh atau sebagian kaki dan jari-jari dari tulang Ibu Jari bagian ruas ujung", "jml_hari_hilang" => 150],
            ["jenis_luka" => "Amputasi seluruh atau sebagian kaki dan jari-jari dari tulang Ibu Jari bagian ruas pangkal", "jml_hari_hilang" => 300],
            ["jenis_luka" => "Amputasi seluruh atau sebagian kaki dan jari-jari dari tulang Jari-jari lainnya bagian ruas ujung", "jml_hari_hilang" => 35],
            ["jenis_luka" => "Amputasi seluruh atau sebagian kaki dan jari-jari dari tulang Jari-jari lainnya bagian ruas tengah", "jml_hari_hilang" => 75],
            ["jenis_luka" => "Amputasi seluruh atau sebagian kaki dan jari-jari dari tulang Jari-jari lainnya bagian ruas pangkal", "jml_hari_hilang" => 150],
            ["jenis_luka" => "Amputasi seluruh atau sebagian kaki dan jari-jari dari tulang Telapak (antara jari- jari pangkal kaki) bagian Ibu Jari", "jml_hari_hilang" => 600],
            ["jenis_luka" => "Amputasi seluruh atau sebagian kaki dan jari-jari dari tulang Telapak (antara jari- jari pangkal kaki) bagian Jari-jari lainnya", "jml_hari_hilang" => 350],
            ["jenis_luka" => "Amputasi seluruh atau sebagian kaki dan jari-jari dari tulang Kaki sampai pergelangan", "jml_hari_hilang" => 2400],
            ["jenis_luka" => "Cacat tetap tiap bagian dari pergelangan sampai siku", "jml_hari_hilang" => 3600],
            ["jenis_luka" => "Cacat tetap dari atas siku sampai sambungan bahu", "jml_hari_hilang" => 4500],
            ["jenis_luka" => "Cacat tetap tiap bagian lengan dari pergelangan sampai siku", "jml_hari_hilang" => 3600],
            ["jenis_luka" => "Cacat tetap tiap bagian dari atas siku sampai sambungan bahu", "jml_hari_hilang" => 4500],
            ["jenis_luka" => "Cacat tetap Tungkai tiap bagian di atas mata kaki sampai lutut", "jml_hari_hilang" => 3000],
            ["jenis_luka" => "Cacat tetap Tungkai tiap bagian di atas lutut sampai pangkal paha", "jml_hari_hilang" => 4500],
            ["jenis_luka" => "Kehilangan fungsi Satu mata", "jml_hari_hilang" => 1800],
            ["jenis_luka" => "Kehilangan fungsi Kedua mata dalam satu kasus kecelakaan", "jml_hari_hilang" => 6000],
            ["jenis_luka" => "Kehilangan fungsi Satu telinga", "jml_hari_hilang" => 600],
            ["jenis_luka" => "Kehilangan fungsi Kedua telinga dalam satu kecelakaan", "jml_hari_hilang" => 3000],
            ["jenis_luka" => "Lumpuh total yang menetap", "jml_hari_hilang" => 6000],
            ["jenis_luka" => "Meninggal dunia", "jml_hari_hilang" => 6000],
        
        ];

        DB::table('jumlah_hari_hilang')->insert($data);
    }
}
