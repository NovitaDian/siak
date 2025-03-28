<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gl_Account extends Model
{
    use HasFactory;
    protected $table = 'gl_account';

     // Optionally specify the primary key if it's not 'id'
     protected $primaryKey = 'id';
 
     // If your primary key is not an integer, set this to false
     public $incrementing = true;
 
     // If you don't have timestamps in your table, set this to false
     public $timestamps = false;
     protected $fillable = [
         'gl_code',
         'gl_name',
         'description',
        

    ];


    
}
