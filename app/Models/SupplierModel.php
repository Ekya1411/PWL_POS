<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierModel extends Model
{
    use HasFactory;

    protected $table = 'm_supplier'; // Pastikan Laravel menggunakan tabel yang benar
    protected $primaryKey = 'supplier_id'; // Sesuaikan dengan primary key di tabel

    protected $fillable = ['supplier_kode', 'supplier_nama', 'supplier_alamat']; // Kolom yang dapat diisi
}