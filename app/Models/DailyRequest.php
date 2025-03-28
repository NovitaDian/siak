<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'sent_daily_id',
        'nama_pengirim',
        'type',
        'reason',
        'status',
    ];
    
    public function daily()
    {
        return $this->belongsTo(SentDaily::class, 'sent_daily_id'); 
    }

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }


   

}