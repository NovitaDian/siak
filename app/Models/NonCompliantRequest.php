<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonCompliantRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'sent_non_compliant_id',
        'user_id',
        'type',
        'reason',
        'status',
    ];

    public function non_compliant()
    {
        return $this->belongsTo(NonCompliant::class, 'sent_non_compliant_id');
    }

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
