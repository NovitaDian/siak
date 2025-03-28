<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daily extends Model
{
    use HasFactory;
    protected $table = 'daily_draft';

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'tanggal_shift_kerja',
        'shift_kerja',
        'nama_hs_officer',
        'rincian_laporan',
        'writer'
    ];
}
