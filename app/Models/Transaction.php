<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction';  // Nama tabel
    protected $primaryKey = 'id';      // Primary key tabel

    protected $fillable = [
        'barang_id', 'tanggal', 'quantity', 'type', 'unit', 'keterangan'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
