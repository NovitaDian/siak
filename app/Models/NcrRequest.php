<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NcrRequest extends Model
{
    use HasFactory;
    protected $table = 'ncr_request';

    protected $fillable = [
        'sent_ncr_id',
        'user_id',
        'nama_pengirim',
        'type',
        'reason',
        'status',
    ];

    public function ncr()
    {
        return $this->belongsTo(SentNcr::class, 'sent_ncr_id'); // Pastikan 'ncr_id' adalah nama kolom foreign key di tabel ncr_requests
    }

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
