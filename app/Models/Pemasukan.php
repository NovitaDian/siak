<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{
    use HasFactory;

    protected $table = 'pemasukan';  // Nama tabel
    protected $primaryKey = 'id';    // Primary key tabel

    protected $fillable = [
        'barang_id', 'tanggal', 'quantity', 'unit', 'keterangan'
    ];

    // Relasi dengan model Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

   
}
