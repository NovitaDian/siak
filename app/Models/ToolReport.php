<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolReport extends Model
{
    use HasFactory;


    protected $table = 'tool_report_draft';

   
    protected $fillable = [
        'alat_terpakai',
        'kondisi_fisik',
        'fungsi_kerja',
        'sertifikasi',
        'kebersihan',
        'tanggal_pemeriksaan',
        'label_petunjuk',
        'pemeliharaan',
        'keamanan_pengguna',
        'catatan',
    ];

}
