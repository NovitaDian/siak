<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'sent_incident_id',
        'user_id',
        '',
        'type',
        'reason',
        'status',
    ];

    public function incident()
    {
        return $this->belongsTo(SentIncident::class, 'sent_incident_id');
    }

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
