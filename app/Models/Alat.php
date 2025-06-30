<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    protected $table = 'alats';

    protected $fillable = [
        'nama_alat_id',
        'nama_alat',
        'nomor',
        'waktu_inspeksi',
        'durasi_inspeksi',
        'status',
    ];
    public function namaAlat()
    {
        return $this->belongsTo(NamaAlat::class, 'nama_alat_id');
    }
}
