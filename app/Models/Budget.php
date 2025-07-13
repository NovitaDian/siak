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
        'gl_id',
        'setahun_total',
        'kategori',
        'year',

    ];
    public function gls()
    {
        return $this->belongsTo(Gl_Account::class, 'gl_id');
    }
    public function prs()
    {
        return $this->hasMany(PurchaseRequest::class, 'budget_id');
    }

    // Total yang sudah dipakai
    public function getUsageAttribute()
    {
        return $this->prs()->sum('valuation_price');
    }

    // Sisa budget
    public function getSisaAttribute()
    {
        return $this->setahun_total - $this->usage;
    }

    // Persentase pemakaian
    public function getPercentageUsageAttribute()
    {
        if ($this->setahun_total == 0) return 0;
        return ($this->usage / $this->setahun_total) * 100;
    }
}
