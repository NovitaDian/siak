<?php
namespace App\Models;
use App\Models\Barang;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';  // Nama tabel
    protected $primaryKey = 'id';     // Primary key tabel

    protected $fillable = [
        'barang_id', 'tanggal', 'quantity', 'unit', 'keterangan'
    ];

    // Relasi dengan model Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    // Event untuk mencatat transaksi pengeluaran ke tabel transaksi
  
}
