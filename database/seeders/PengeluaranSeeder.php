<?php
namespace Database\Seeders; 
use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Pengeluaran;
use App\Models\Transaction;
use Carbon\Carbon;

class PengeluaranSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua barang yang sudah ada
        $barangs = Barang::all();

        foreach ($barangs as $barang) {
            // Tentukan jumlah pengeluaran (maksimal tidak melebihi stok saat ini)
            $maxQty = min($barang->quantity, rand(1, 10));

            // Lewati jika stoknya nol atau tidak cukup
            if ($maxQty <= 0) {
                continue;
            }

            $tanggal = Carbon::now()->subDays(rand(1, 5))->format('Y-m-d');
            $keterangan = 'Pengeluaran dummy seeder';

            // Simpan ke tabel pengeluaran
            $pengeluaran = new Pengeluaran();
            $pengeluaran->barang_id = $barang->id;
            $pengeluaran->quantity = $maxQty;
            $pengeluaran->unit = $barang->unit;
            $pengeluaran->keterangan = $keterangan;
            $pengeluaran->tanggal = $tanggal;
            $pengeluaran->save();

            // Update stok barang
            $barang->quantity -= $maxQty;
            $barang->save();

            // Simpan ke tabel transaksi
            $transaction = new Transaction();
            $transaction->barang_id = $barang->id;
            $transaction->quantity = $maxQty;
            $transaction->unit = $barang->unit;
            $transaction->keterangan = $keterangan;
            $transaction->tanggal = $tanggal;
            $transaction->type = 'Pengeluaran';
            $transaction->save();
        }
    }
}
