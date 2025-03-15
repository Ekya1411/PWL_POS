<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelModel extends Model
{
    use HasFactory;

    protected $table = 'm_level'; // Pastikan Laravel menggunakan tabel yang benar
    protected $primaryKey = 'level_id'; // Sesuaikan dengan primary key di tabel

    protected $fillable = ['level_id', 'level_kode', 'level_nama']; // Kolom yang dapat diisi

    public function user():BelongsTo {
        return $this->belongsTo(UserModel::class);
    }
}
