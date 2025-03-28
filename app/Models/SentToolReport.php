<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentToolReport extends Model
{
    use HasFactory;


    protected $table = 'tool_report_fix';

   
    protected $fillable = [
        'alat_terpakai',
        'kondisi_fisik',
        'fungsi_kerja',
        'sertifikasi',
        'kebersihan',
        'tanggal_pemeriksaan',
        'catatan',
        'writer',
    ];

}
