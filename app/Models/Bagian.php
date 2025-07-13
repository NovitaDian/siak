<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bagian extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan oleh model ini
    protected $table = 'bagian_perusahaan';

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'perusahaan_id',
        'nama_bagian'
    ];

   public function per()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }
}
