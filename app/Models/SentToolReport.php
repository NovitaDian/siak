<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentToolReport extends Model
{
    use HasFactory;


    protected $table = 'tool_inspections_fix';


    protected $fillable = [
        'user_id',
        'alat_id',
        'hse_inspector_id',
        'tanggal_pemeriksaan',
        'status_pemeriksaan',
        'status',
        'foto',
    ];
    public function inspector()
    {
        return $this->belongsTo(HseInspector::class, 'hse_inspector_id');
    }
    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id');
    }

    public function namaAlat()
    {
        return $this->belongsTo(NamaAlat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
