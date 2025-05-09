<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KategoriModel;

class BarangModel extends Model
{
    use HasFactory;
    protected $table = 'm_barang'; // Pastikan Laravel menggunakan tabel yang benar
    protected $primaryKey = 'barang_id'; // Sesuaikan dengan primary key di tabel

    protected $fillable = ['kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual', 'image']; // Kolom yang dapat diisi

    public function kategori()
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id');
    }

    public function stok()
    {
        return $this->hasMany(StokModel::class, 'barang_id');
    }

    public function getTotalStokKeluar()
    {
        return $this->hasMany(TransaksiDetailModel::class, 'barang_id');
    }

    public function getTotalStok()
    {
        $stok = $this->stok()->sum('stok_jumlah') - $this->getTotalStokKeluar()->sum('jumlah');
        return $stok;
    }
}