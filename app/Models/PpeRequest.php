<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'sent_ppe_id',
        'nama_pengirim',
        'type',
        'reason',
        'status',
    ];
    
    public function ppe()
    {
        return $this->belongsTo(SentPpe::class, 'sent_ppe_id'); // Pastikan 'ppe_id' adalah nama kolom foreign key di tabel ppe_requests
    }

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }


   

}