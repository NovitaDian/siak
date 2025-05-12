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
        'material_code', 'material_group','material_group_id', 'description', 'image', 'material_type', 'remark', 'quantity', 'unit','unit_id'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'barang_id');
    }

    // Fungsi untuk menambahkan atau mengurangi quantity barang
    
}
