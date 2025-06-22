<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;
    protected $table = 'budget';

    // Optionally specify the primary key if it's not 'id'
    protected $primaryKey = 'id';

    // If your primary key is not an integer, set this to false
    public $incrementing = true;

    // If you don't have timestamps in your table, set this to false
    public $timestamps = false;
    protected $fillable = [
        'internal_order',
        'gl_code',
        'gl_name',
        'setahun_total',
        'kategori',
        'year',
        'is_main'

    ];

    public function purchaseRequests()
    {
        return $this->hasMany(PurchaseRequest::class, 'budget_id');
    }
}
