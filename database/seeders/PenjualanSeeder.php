<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['user_id' => 1, 'pembeli' => 'John Doe', 'penjualan_kode' => 'PJ-0001', 'penjualan_tanggal' => '2024-02-01 10:00:00'],
            ['user_id' => 2, 'pembeli' => 'Jane Smith', 'penjualan_kode' => 'PJ-0002', 'penjualan_tanggal' => '2024-02-02 11:30:00'],
            ['user_id' => 3, 'pembeli' => 'Michael Brown', 'penjualan_kode' => 'PJ-0003', 'penjualan_tanggal' => '2024-02-03 09:45:00'],
            ['user_id' => 1, 'pembeli' => 'Emily Davis', 'penjualan_kode' => 'PJ-0004', 'penjualan_tanggal' => '2024-02-04 14:15:00'],
            ['user_id' => 2, 'pembeli' => 'David Wilson', 'penjualan_kode' => 'PJ-0005', 'penjualan_tanggal' => '2024-02-05 16:00:00'],
            ['user_id' => 3, 'pembeli' => 'Sophia Martinez', 'penjualan_kode' => 'PJ-0006', 'penjualan_tanggal' => '2024-02-06 12:20:00'],
            ['user_id' => 1, 'pembeli' => 'James Anderson', 'penjualan_kode' => 'PJ-0007', 'penjualan_tanggal' => '2024-02-07 10:50:00'],
            ['user_id' => 2, 'pembeli' => 'Olivia Thomas', 'penjualan_kode' => 'PJ-0008', 'penjualan_tanggal' => '2024-02-08 13:40:00'],
            ['user_id' => 3, 'pembeli' => 'Benjamin Harris', 'penjualan_kode' => 'PJ-0009', 'penjualan_tanggal' => '2024-02-09 17:25:00'],
            ['user_id' => 1, 'pembeli' => 'Charlotte Clark', 'penjualan_kode' => 'PJ-0010', 'penjualan_tanggal' => '2024-02-10 15:10:00'],
        ];

        foreach ($data as &$item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
        }

        DB::table('t_penjualan')->insert($data);
    }
}
