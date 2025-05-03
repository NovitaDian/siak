<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonCompliant extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan oleh model
    protected $table = 'non_compliants';

    // Kolom yang bisa diisi (fillable)
    protected $fillable = [
        'id_ppe',
        'nama_hse_inspector',
        'shift_kerja',
        'jam_pengawasan',
        'zona_pengawasan',
        'lokasi_observasi',
        'tipe_observasi',
        'deskripsi_ketidaksesuaian',
        'nama_pelanggar',
        'perusahaan',
        'nama_bagian',
        'tindakan',
        'writter',
    ];

    // Definisikan relasi dengan model PpeFix (foreign key)
    public function ppeFix()
    {
        return $this->belongsTo(SentPpe::class, 'id_ppe', 'id');
    }
}
