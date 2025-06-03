<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;

class Ncr extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan oleh model ini
    protected $table = 'ncr';

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'writer',
        'tanggal_shift_kerja',
        'shift_kerja',
        'nama_hs_officer_1',
        'nama_hs_officer_2',
        'tanggal_audit',
        'nama_auditee',
        'perusahaan',
        'nama_bagian',
        'element_referensi_ncr',
        'kategori_ketidaksesuaian',
        'deskripsi_ketidaksesuaian',
        'estimasi',
        'foto',
        'user_id',
        'tindak_lanjut'
    ];

    // Menentukan tipe data untuk tanggal agar bisa otomatis di-convert menjadi format date
    protected $dates = [
        'tanggal_shift_kerja',
        'tanggal_audit',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
