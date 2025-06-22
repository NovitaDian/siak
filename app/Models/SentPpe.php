<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentPpe extends Model
{
    use HasFactory;

    protected $table = 'ppe_fix';

    // Daftar kolom yang dapat diisi secara massal
    protected $fillable = [
        'writer',
        'user_id',
        'tanggal_shift_kerja',
        'shift_kerja',
        'status_ppe',
        'nama_hse_inspector',
        'hse_inspector_id',
        'jam_mulai',
        'jam_selesai',
        'zona_pengawasan',
        'lokasi_observasi',
        'jumlah_patuh_apd_karyawan',
        'jumlah_tidak_patuh_helm_karyawan',
        'jumlah_tidak_patuh_sepatu_karyawan',
        'jumlah_tidak_patuh_pelindung_mata_karyawan',
        'jumlah_tidak_patuh_safety_harness_karyawan',
        'jumlah_tidak_patuh_apd_lainnya_karyawan',
        'jumlah_patuh_apd_kontraktor',
        'jumlah_tidak_patuh_helm_kontraktor',
        'jumlah_tidak_patuh_sepatu_kontraktor',
        'jumlah_tidak_patuh_pelindung_mata_kontraktor',
        'jumlah_tidak_patuh_safety_harness_kontraktor',
        'jumlah_tidak_patuh_apd_lainnya_kontraktor',
        'keterangan_tidak_patuh',
        'status'
    ];
    public function requests()
    {
        return $this->hasMany(PpeRequest::class);
    }
    public function nonCompliants()
    {
        return $this->hasMany(NonCompliant::class, 'id_ppe', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
