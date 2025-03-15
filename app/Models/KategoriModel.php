<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriModel extends Model
{
    use HasFactory;
    protected $table = 'm_kategori'; // Pastikan Laravel menggunakan tabel yang benar
    protected $primaryKey = 'kategori_id'; // Sesuaikan dengan primary key di tabel

    protected $fillable = ['kategori_id', 'kategori_kode', 'kategori_nama']; // Kolom yang dapat diisi

    public function barang()
    {
        return $this->belongsTo(BarangModel::class, 'kategori_id', 'kategori_id');
    }
}