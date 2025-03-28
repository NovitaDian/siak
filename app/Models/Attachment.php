<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Prompts\Note;

class Attachment extends Model
{
    use HasFactory;
    protected $table = 'attachments';

    protected $fillable = [
        'note_id',
        'file_name',
        'file_path',
    ];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}