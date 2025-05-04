<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['user_id' => 1, 'pembeli' => 'John Doe', 'penjualan_kode' => 'PJ-0001'],
            ['user_id' => 3, 'pembeli' => 'Jane Smith', 'penjualan_kode' => 'PJ-0002'],
            ['user_id' => 3, 'pembeli' => 'Michael Brown', 'penjualan_kode' => 'PJ-0003'],
            ['user_id' => 5, 'pembeli' => 'Emily Davis', 'penjualan_kode' => 'PJ-0004'],
            ['user_id' => 6, 'pembeli' => 'David Wilson', 'penjualan_kode' => 'PJ-0005'],
            ['user_id' => 7, 'pembeli' => 'Sophia Martinez', 'penjualan_kode' => 'PJ-0006'],
            ['user_id' => 6, 'pembeli' => 'James Anderson', 'penjualan_kode' => 'PJ-0007'],
            ['user_id' => 6, 'pembeli' => 'Olivia Thomas', 'penjualan_kode' => 'PJ-0008'],
            ['user_id' => 9, 'pembeli' => 'Benjamin Harris', 'penjualan_kode' => 'PJ-0009'],
            ['user_id' => 2, 'pembeli' => 'Charlotte Clark', 'penjualan_kode' => 'PJ-0010'],
        ];

        foreach ($data as &$item) {
            $item['penjualan_tanggal'] = Carbon::createFromTimestamp(
                rand(strtotime('2024-10-01'), time())
            )->format('Y-m-d H:i:s');
            $item['created_at'] = now();
            $item['updated_at'] = now();
        }

        DB::table('t_penjualan')->insert($data);
    }
}
