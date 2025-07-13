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
        'user_id',
        'shift_kerja',
        'jam_mulai',
        'jam_selesai',
        'zona_pengawasan',
        'lokasi_observasi',
        'tipe_observasi',
        'deskripsi_ketidaksesuaian',
        'nama_pelanggar',
        'perusahaan_id',
        'nama_bagian',
        'tindakan',

        'foto',
        'status',
    ];

    // Definisikan relasi dengan model PpeFix (foreign key)
    public function ppeFix()
    {
        return $this->belongsTo(SentPpe::class, 'id_ppe', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function inspector()
    {
        return $this->belongsTo(HseInspector::class, 'hse_inspector_id');
    }
    public function pers()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }
}
