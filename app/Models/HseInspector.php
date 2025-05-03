<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HseInspector extends Model
{
    use HasFactory;

    protected $table = 'hse_inspector';

    protected $fillable = [
        'name',
        'jabatan',
    ];
}
