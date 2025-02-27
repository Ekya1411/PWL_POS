<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['barang_id' => 1, 'user_id' => 1, 'stok_tanggal' => '2024-02-01', 'stok_jumlah' => 10],
            ['barang_id' => 2, 'user_id' => 1, 'stok_tanggal' => '2024-02-02', 'stok_jumlah' => 15],
            ['barang_id' => 3, 'user_id' => 2, 'stok_tanggal' => '2024-02-03', 'stok_jumlah' => 20],
            ['barang_id' => 4, 'user_id' => 2, 'stok_tanggal' => '2024-02-04', 'stok_jumlah' => 8],
            ['barang_id' => 5, 'user_id' => 3, 'stok_tanggal' => '2024-02-05', 'stok_jumlah' => 12],
            ['barang_id' => 6, 'user_id' => 1, 'stok_tanggal' => '2024-02-06', 'stok_jumlah' => 30],
            ['barang_id' => 7, 'user_id' => 2, 'stok_tanggal' => '2024-02-07', 'stok_jumlah' => 25],
            ['barang_id' => 8, 'user_id' => 3, 'stok_tanggal' => '2024-02-08', 'stok_jumlah' => 10],
            ['barang_id' => 9, 'user_id' => 1, 'stok_tanggal' => '2024-02-09', 'stok_jumlah' => 18],
            ['barang_id' => 10, 'user_id' => 2, 'stok_tanggal' => '2024-02-10', 'stok_jumlah' => 22],
            ['barang_id' => 11, 'user_id' => 3, 'stok_tanggal' => '2024-02-11', 'stok_jumlah' => 5],
            ['barang_id' => 12, 'user_id' => 1, 'stok_tanggal' => '2024-02-12', 'stok_jumlah' => 14],
            ['barang_id' => 13, 'user_id' => 2, 'stok_tanggal' => '2024-02-13', 'stok_jumlah' => 17],
            ['barang_id' => 14, 'user_id' => 3, 'stok_tanggal' => '2024-02-14', 'stok_jumlah' => 9],
            ['barang_id' => 15, 'user_id' => 1, 'stok_tanggal' => '2024-02-15', 'stok_jumlah' => 11],
        ];

        foreach ($data as &$item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
        }

        DB::table('t_stok')->insert($data);
    }
}
