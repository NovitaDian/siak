<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ppe extends Model
{
    use HasFactory;

    protected $table = 'ppe_draft';

    // Daftar kolom yang dapat diisi secara massal
    protected $fillable = [
        'tanggal_shift_kerja',
        'shift_kerja',
        'nama_hse_inspector',
        'jam_pengawasan',
        'zona_pengawasan',
        'lokasi_observasi',
        'jumlah_patuh_apd_karyawan',
        'jumlah_tidak_patuh_helm_karyawan',
        'jumlah_tidak_patuh_sepatu_karyawan',
        'jumlah_tidak_patuh_pelindung_mata_karyawan',
        'jumlah_tidak_patuh_safety_harness_karyawan',
        'jumlah_tidak_patuh_apd_lainnya_karyawan',
        'keterangan_tidak_patuh_karyawan',
        'jumlah_patuh_apd_kontraktor',
        'jumlah_tidak_patuh_helm_kontraktor',
        'jumlah_tidak_patuh_sepatu_kontraktor',
        'jumlah_tidak_patuh_pelindung_mata_kontraktor',
        'jumlah_tidak_patuh_safety_harness_kontraktor',
        'jumlah_tidak_patuh_apd_lainnya_kontraktor',
        'keterangan_tidak_patuh'
    ];
}
