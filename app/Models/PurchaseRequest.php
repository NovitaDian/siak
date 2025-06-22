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
        'pr_no',
        'pr_category',
        'purchase_for',
        'material',
        'quantity',
        'unit',
        'valuation_price',
        'gl_code',
        'gl_name',
        'cost_center',
        'total_price',
        'io_assetcode',
        'budget_id',
    ];

    protected $dates = ['pr_date'];
    public function glAccount()
    {
        return $this->belongsTo(Gl_Account::class, 'gl_code', 'gl_account');
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class, 'budget_id');
    }
}
