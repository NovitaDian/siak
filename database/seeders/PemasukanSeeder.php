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
                'material_name' => 'Topi safety',
                'unit' => 'PCS',
            ],
            [
                'material_code' => '2309202K63988',
                'material_name' => 'Sepatu safety',
                'unit' => 'PCS',
            ],
            [
                'material_code' => '2309202K63789',
                'material_name' => 'Thermometer',
                'unit' => 'PCS',
            ],
            [
                'material_code' => '2309202K63784',
                'material_name' => 'Sarung Tangan Kain',
                'unit' => 'PAC',
            ],
        ];

        foreach ($barangList as $item) {
            // Cek atau buat barang
            $barang = Barang::firstOrCreate(
                ['material_code' => $item['material_code']],
                [
                    'material_name' => $item['material_name'],
                    'unit' => $item['unit'],
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
            $pemasukan->unit = $barang->unit;
            $pemasukan->keterangan = $keterangan;
            $pemasukan->tanggal = $tanggal;
            $pemasukan->save();

            // Update stok barang
            $barang->quantity += $quantity;
            $barang->save();

            // Simpan ke tabel transaksi
            $transaction = new Transaction();
            $transaction->barang_id = $barang->id;
            $transaction->quantity = $quantity;
            $transaction->unit = $barang->unit;
            $transaction->keterangan = $keterangan;
            $transaction->tanggal = $tanggal;
            $transaction->type = 'Pemasukan';
            $transaction->save();
        }
    }
}
