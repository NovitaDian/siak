<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentDaily extends Model
{
    use HasFactory;
    protected $table = 'daily_fix';

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'user_id',
        'tanggal_shift_kerja',
        'shift_kerja',
        'hse_inspector_id',

        'rincian_laporan',
        'status'
    ];
    public function requests()
    {
        return $this->hasMany(DailyRequest::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function inspectors()
    {
        return $this->belongsTo(HseInspector::class, 'hse_inspector_id');
    }
}
