<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentNcr extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan oleh model ini
    protected $table = 'ncr_fix';

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [

        'user_id',
        'tanggal_shift_kerja',
        'shift_kerja',
        'nama_hs_officer_1',
        'nama_hs_officer_2',
        'tanggal_audit',
        'nama_auditee',
        'perusahaan_id',
        'nama_bagian',
        'element_referensi_ncr',
        'kategori_ketidaksesuaian',
        'deskripsi_ketidaksesuaian',
        'status',
        'status_ncr',
        'status_note',
        'durasi_ncr',
        'estimasi',
        'foto',
        'foto_closed',
        'tindak_lanjut',
        'waktu_closed'
    ];

    // Menentukan tipe data untuk tanggal agar bisa otomatis di-convert menjadi format date
    protected $dates = [
        'tanggal_shift_kerja',
        'tanggal_audit',
    ];
    public function requests()
    {
        return $this->hasMany(NcrRequest::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function pers()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }
}
