<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NamaAlat extends Model
{
    protected $table = 'nama_alats';

    protected $fillable = [
        'nama_alat',
    ];
      public function alats()
    {
        return $this->hasMany(Alat::class);
    }
}
