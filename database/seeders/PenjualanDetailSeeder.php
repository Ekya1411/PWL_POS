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
            ['penjualan_id' => 1, 'barang_id' => 1, 'harga' => 17000000 * 2, 'jumlah' => 2],
            ['penjualan_id' => 1, 'barang_id' => 2, 'harga' => 1000000 * 1, 'jumlah' => 1],
            ['penjualan_id' => 1, 'barang_id' => 3, 'harga' => 2300000 * 3, 'jumlah' => 3],

            // Penjualan 2
            ['penjualan_id' => 2, 'barang_id' => 4, 'harga' => 1500000 * 1, 'jumlah' => 1],
            ['penjualan_id' => 2, 'barang_id' => 5, 'harga' => 1200000 * 2, 'jumlah' => 2],
            ['penjualan_id' => 2, 'barang_id' => 6, 'harga' => 900000 * 3, 'jumlah' => 3],

            // Penjualan 3
            ['penjualan_id' => 3, 'barang_id' => 7, 'harga' => 1800000 * 2, 'jumlah' => 2],
            ['penjualan_id' => 3, 'barang_id' => 8, 'harga' => 700000 * 1, 'jumlah' => 1],
            ['penjualan_id' => 3, 'barang_id' => 9, 'harga' => 2800000 * 1, 'jumlah' => 1],

            // Penjualan 4
            ['penjualan_id' => 4, 'barang_id' => 10, 'harga' => 3500000 * 2, 'jumlah' => 2],
            ['penjualan_id' => 4, 'barang_id' => 11, 'harga' => 80000 * 3, 'jumlah' => 3],
            ['penjualan_id' => 4, 'barang_id' => 12, 'harga' => 200000 * 1, 'jumlah' => 1],

            // Penjualan 5
            ['penjualan_id' => 5, 'barang_id' => 13, 'harga' => 400000 * 2, 'jumlah' => 2],
            ['penjualan_id' => 5, 'barang_id' => 14, 'harga' => 150000 * 3, 'jumlah' => 3],
            ['penjualan_id' => 5, 'barang_id' => 15, 'harga' => 320000 * 2, 'jumlah' => 2],

            // Penjualan 6
            ['penjualan_id' => 6, 'barang_id' => 16, 'harga' => 25000 * 2, 'jumlah' => 2],
            ['penjualan_id' => 6, 'barang_id' => 17, 'harga' => 35000 * 1, 'jumlah' => 1],
            ['penjualan_id' => 6, 'barang_id' => 18, 'harga' => 75000 * 1, 'jumlah' => 1],

            // Penjualan 7
            ['penjualan_id' => 7, 'barang_id' => 19, 'harga' => 45000 * 2, 'jumlah' => 2],
            ['penjualan_id' => 7, 'barang_id' => 1, 'harga' => 17000000 * 1, 'jumlah' => 1],
            ['penjualan_id' => 7, 'barang_id' => 2, 'harga' => 1000000 * 3, 'jumlah' => 3],

            // Penjualan 8
            ['penjualan_id' => 8, 'barang_id' => 3, 'harga' => 2300000 * 1, 'jumlah' => 1],
            ['penjualan_id' => 8, 'barang_id' => 4, 'harga' => 1500000 * 2, 'jumlah' => 2],
            ['penjualan_id' => 8, 'barang_id' => 5, 'harga' => 1200000 * 1, 'jumlah' => 1],

            // Penjualan 9
            ['penjualan_id' => 9, 'barang_id' => 6, 'harga' => 900000 * 2, 'jumlah' => 2],
            ['penjualan_id' => 9, 'barang_id' => 7, 'harga' => 1800000 * 1, 'jumlah' => 1],
            ['penjualan_id' => 9, 'barang_id' => 8, 'harga' => 700000 * 2, 'jumlah' => 2],

            // Penjualan 10
            ['penjualan_id' => 10, 'barang_id' => 9, 'harga' => 2800000 * 1, 'jumlah' => 1],
            ['penjualan_id' => 10, 'barang_id' => 10, 'harga' => 3500000 * 1, 'jumlah' => 1],
            ['penjualan_id' => 10, 'barang_id' => 11, 'harga' => 80000 * 2, 'jumlah' => 2],
        ];

        foreach ($data as &$item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
        }

        DB::table('t_penjualan_detail')->insert($data);
    }
}