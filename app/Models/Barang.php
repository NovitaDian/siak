<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';  // Nama tabel
    protected $primaryKey = 'id'; // Primary key tabel

    protected $fillable = [
        'material_code','material_group_id', 'description', 'image', 'material_type', 'remark', 'quantity','unit_id'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'barang_id');
    }

    // Fungsi untuk menambahkan atau mengurangi quantity barang
     public function unitId()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
     public function materialGroup()
    {
        return $this->belongsTo(MaterialGroup::class, 'material_group_id');
    }
}
