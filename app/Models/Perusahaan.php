<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan oleh model ini
    protected $table = 'perusahaan';

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'perusahaan_code',
        'perusahaan_name',
        'city',
        'street'
    ];

   
}
