<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolRequest extends Model
{
    use HasFactory;
    protected $table = 'tool_inspections_request';

    protected $fillable = [
        'sent_tool_id',
        'nama_pengirim',
        'user_id',
        'type',
        'reason',
        'status',
    ];

    public function tool()
    {
        return $this->belongsTo(SentPpe::class, 'sent_tool_id');
    }
    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
