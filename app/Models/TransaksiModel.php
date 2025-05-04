<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiModel extends Model
{
    use HasFactory;
    protected $table = 't_penjualan';
    protected $primaryKey = 'penjualan_id';
    protected $fillable = ['penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal'];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function detail_transaksi()
    {
        return $this->hasMany(TransaksiDetailModel::class, 'penjualan_id');
    }

    public function getTotalAttribute()
    {
        return $this->detail_transaksi->sum(function ($item) {
            return $item->barang->harga_jual * $item->jumlah;
        });
    }
}
