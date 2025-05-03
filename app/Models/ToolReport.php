<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolReport extends Model
{
    use HasFactory;


    protected $table = 'tool_inspections_draft';


    protected $fillable = [
        'writer',
        'nama_alat',
        'alat_id',
        'hse_inspector_id',
        'hse_inspector',
        'tanggal_pemeriksaan',
        'status',
    ];
    public function inspector()
    {
        return $this->belongsTo(HseInspector::class, 'hse_inspector_id');
    }
}
