<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Penjualan 1
            ['penjualan_id' => 1, 'barang_id' => 1, 'harga' => 10000, 'jumlah' => 2],
            ['penjualan_id' => 1, 'barang_id' => 2, 'harga' => 15000, 'jumlah' => 1],
            ['penjualan_id' => 1, 'barang_id' => 3, 'harga' => 20000, 'jumlah' => 3],

            // Penjualan 2
            ['penjualan_id' => 2, 'barang_id' => 4, 'harga' => 25000, 'jumlah' => 1],
            ['penjualan_id' => 2, 'barang_id' => 5, 'harga' => 30000, 'jumlah' => 2],
            ['penjualan_id' => 2, 'barang_id' => 6, 'harga' => 12000, 'jumlah' => 4],

            // Penjualan 3
            ['penjualan_id' => 3, 'barang_id' => 7, 'harga' => 5000, 'jumlah' => 5],
            ['penjualan_id' => 3, 'barang_id' => 8, 'harga' => 7500, 'jumlah' => 3],
            ['penjualan_id' => 3, 'barang_id' => 9, 'harga' => 9000, 'jumlah' => 2],

            // Penjualan 4
            ['penjualan_id' => 4, 'barang_id' => 10, 'harga' => 11000, 'jumlah' => 1],
            ['penjualan_id' => 4, 'barang_id' => 11, 'harga' => 13000, 'jumlah' => 2],
            ['penjualan_id' => 4, 'barang_id' => 12, 'harga' => 14000, 'jumlah' => 3],

            // Penjualan 5
            ['penjualan_id' => 5, 'barang_id' => 13, 'harga' => 22000, 'jumlah' => 2],
            ['penjualan_id' => 5, 'barang_id' => 14, 'harga' => 25000, 'jumlah' => 1],
            ['penjualan_id' => 5, 'barang_id' => 15, 'harga' => 18000, 'jumlah' => 4],

            // Penjualan 6
            ['penjualan_id' => 6, 'barang_id' => 1, 'harga' => 10000, 'jumlah' => 3],
            ['penjualan_id' => 6, 'barang_id' => 2, 'harga' => 15000, 'jumlah' => 2],
            ['penjualan_id' => 6, 'barang_id' => 3, 'harga' => 20000, 'jumlah' => 1],

            // Penjualan 7
            ['penjualan_id' => 7, 'barang_id' => 4, 'harga' => 25000, 'jumlah' => 4],
            ['penjualan_id' => 7, 'barang_id' => 5, 'harga' => 30000, 'jumlah' => 3],
            ['penjualan_id' => 7, 'barang_id' => 6, 'harga' => 12000, 'jumlah' => 1],

            // Penjualan 8
            ['penjualan_id' => 8, 'barang_id' => 7, 'harga' => 5000, 'jumlah' => 2],
            ['penjualan_id' => 8, 'barang_id' => 8, 'harga' => 7500, 'jumlah' => 3],
            ['penjualan_id' => 8, 'barang_id' => 9, 'harga' => 9000, 'jumlah' => 4],

            // Penjualan 9
            ['penjualan_id' => 9, 'barang_id' => 10, 'harga' => 11000, 'jumlah' => 2],
            ['penjualan_id' => 9, 'barang_id' => 11, 'harga' => 13000, 'jumlah' => 1],
            ['penjualan_id' => 9, 'barang_id' => 12, 'harga' => 14000, 'jumlah' => 3],

            // Penjualan 10
            ['penjualan_id' => 10, 'barang_id' => 13, 'harga' => 22000, 'jumlah' => 5],
            ['penjualan_id' => 10, 'barang_id' => 14, 'harga' => 25000, 'jumlah' => 2],
            ['penjualan_id' => 10, 'barang_id' => 15, 'harga' => 18000, 'jumlah' => 1],
        ];

        foreach ($data as &$item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
        }

        DB::table('t_penjualan_detail')->insert($data);
    }
}