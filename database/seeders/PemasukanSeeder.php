<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Pemasukan;
use App\Models\Transaction;
use Carbon\Carbon;

class PemasukanSeeder extends Seeder
{
    public function run()
    {
        // Data barang dummy
        $barangList = [
            [
                'material_code' => '2309202K63738',
                'material_group_id' => '1',
                'description' => 'Topi safety',
                'material_type' => 'STD:Stock',
                
            ],
            [
                'material_code' => '2309202K63988',
                'material_group_id' => '2',
                'description' => 'Sepatu safety',
                'material_type' => 'STD:NonStock',
            ],
            [
                'material_code' => '2309202K63789',
                'material_group_id' => '3',
                'description' => 'Thermometer',
                'material_type' => 'STD:NonStock',
            ],
            [
                'material_code' => '2309202K63784',
                'material_group_id' => '4',
                'description' => 'Sarung Tangan Kain',
                'material_type' => 'STD:Stock',
            ],
        ];
            $unit= rand(1, 5);

        foreach ($barangList as $item) {
            // Cek atau buat barang
            $barang = Barang::firstOrCreate(
                ['material_code' => $item['material_code']],
                [
                    'description' => $item['description'],
                    'material_group_id' => $item['material_group_id'],
                    'material_type' => $item['material_type'],
                    'unit_id' => $unit,
                    'quantity' => 0, 
                ]
            );

            // Dummy pemasukan
            $quantity = rand(5, 20); // stok masuk antara 5-20
            $tanggal = Carbon::now()->subDays(rand(0, 10))->format('Y-m-d');
            $keterangan = 'Stok awal seeder';

            // Simpan ke tabel pemasukan
            $pemasukan = new Pemasukan();
            $pemasukan->barang_id = $barang->id;
            $pemasukan->quantity = $quantity;
            $pemasukan->keterangan = $keterangan;
            $pemasukan->tanggal = $tanggal;
            $pemasukan->save();

            // Update stok barang
            $barang->quantity += $quantity;
            $barang->save();

        }
    }
}
