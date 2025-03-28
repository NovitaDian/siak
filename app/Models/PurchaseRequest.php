<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    use HasFactory;

    protected $table = 'pr'; // Pastikan sesuai dengan nama tabel di database

    protected $fillable = [
        'pr_date',
        'plant',
        'pr_no',
        'material_code',
        'short_text',
        'quantity',
        'unit',
        'valuation_price',
        'gl_account',
    ];

    protected $dates = ['pr_date'];
    public function glAccount()
    {
        return $this->belongsTo(Gl_Account::class, 'gl_code', 'gl_account');
    }

    public function budget()
    {
        return $this->hasOne(Budget::class, 'gl_code', 'gl_account');
    }
}
