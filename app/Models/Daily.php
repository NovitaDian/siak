<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daily extends Model
{
    use HasFactory;
    protected $table = 'daily_draft';

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'tanggal_shift_kerja',
        'shift_kerja',
        'hse_inspector_id',
        'rincian_laporan',
        'user_id',
        'writer'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function inspectors()
    {
        return $this->belongsTo(HseInspector::class, 'hse_inspector_id');
    }
}
