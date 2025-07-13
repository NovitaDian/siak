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
    public function ppe()
    {
        return $this->hasMany(SentPpe::class);
    }
    public function nonCompliant()
    {
        return $this->hasMany(NonCompliant::class);
    }
    public function daily()
    {
        return $this->hasMany(Daily::class);
    }
    public function sentDaily()
    {
        return $this->hasMany(SentDaily::class);
    }
    public function incident()
    {
        return $this->hasMany(Incident::class);
    }
    public function sentIncident()
    {
        return $this->hasMany(SentIncident::class);
    }
    public function sentTool()
    {
        return $this->hasMany(SentToolReport::class);
    }
}
