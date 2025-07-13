<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialGroup extends Model
{
    use HasFactory;
    protected $table = 'material_group';

     // Optionally specify the primary key if it's not 'id'
     protected $primaryKey = 'id';
 
     // If your primary key is not an integer, set this to false
     public $incrementing = true;
 
     // If you don't have timestamps in your table, set this to false
     public $timestamps = false;
    protected $fillable = [
        'id',
        'material_group',
    ];
   
public function barang()
{
    return $this->hasMany(Barang::class, 'material_group_id');
}

    
}
