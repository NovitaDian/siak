<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $table = 'unit';

    // Optionally specify the primary key if it's not 'id'
    protected $primaryKey = 'id';

    // If your primary key is not an integer, set this to false
    public $incrementing = true;

    // If you don't have timestamps in your table, set this to false
    public $timestamps = false;

    // Specify which attributes are mass assignable
    protected $fillable = [
        'unit',
        'description',

        // Add other attributes that you want to be mass assignable
    ];

    public function prs()
    {
        return $this->hasMany(PurchaseRequest::class, 'unit_id');
    }
    public function barang()
    {
        return $this->hasMany(Barang::class, 'unit_id');
    }
    
}
