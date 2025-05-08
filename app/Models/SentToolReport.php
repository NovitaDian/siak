<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentToolReport extends Model
{
    use HasFactory;


    protected $table = 'tool_inspections_fix';


    protected $fillable = [
        'draft_id',
        'writer',
        'alat_id',
        'nama_alat',
        'hse_inspector_id',
        'hse_inspector',
        'tanggal_pemeriksaan',
        'status_pemeriksaan',
        'status',
    ];
    public function inspector()
    {
        return $this->belongsTo(HseInspector::class, 'hse_inspector_id');
    }
    public function alat()
{
    return $this->belongsTo(Alat::class, 'alat_id');
}
}
