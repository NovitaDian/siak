<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'writer',
        'note',
    ];

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class, 'note_id');
    }
}
