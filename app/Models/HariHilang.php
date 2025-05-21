<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HariHilang extends Model
{
    protected $table = 'jumlah_hari_hilang';

    protected $fillable = [
        'jenis_luka',
        'jml_hari_hilang',
    ];
}
