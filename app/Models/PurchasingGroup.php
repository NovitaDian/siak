<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasingGroup extends Model
{
    use HasFactory;
    protected $table = 'purchasing_group';

     // Optionally specify the primary key if it's not 'id'
     protected $primaryKey = 'id';
 
     // If your primary key is not an integer, set this to false
     public $incrementing = true;
 
     // If you don't have timestamps in your table, set this to false
     public $timestamps = false;
    protected $fillable = [
      
        'pur_grp',
        'department',
    ];
   

    
}
