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

     public function bagian()
    {
        return $this->hasMany(Bagian::class, 'perusahaan_id');
    }
    public function nonCompliant()
    {
        return $this->belongsTo(NonCompliant::class);
    }
    public function ncr()
    {
        return $this->belongsTo(Ncr::class);
    }
    public function sentNcr()
    {
        return $this->belongsTo(SentNcr::class);
    }
    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }
    public function sentIncident()
    {
        return $this->belongsTo(SentIncident::class);
    }
}
