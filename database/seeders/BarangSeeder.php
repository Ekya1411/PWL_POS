<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Supplier 1
            [
                'kategori_id' => 1,
                'barang_kode' => 'BRG001',
                'barang_nama' => 'Laptop ASUS ROG',
                'harga_beli' => 15000000,
                'harga_jual' => 17000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 1,
                'barang_kode' => 'BRG002',
                'barang_nama' => 'Mouse Logitech G502',
                'harga_beli' => 800000,
                'harga_jual' => 1000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 1,
                'barang_kode' => 'BRG003',
                'barang_nama' => 'Monitor LG 24 Inch',
                'harga_beli' => 2000000,
                'harga_jual' => 2300000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 1,
                'barang_kode' => 'BRG004',
                'barang_nama' => 'Keyboard Mechanical Razer',
                'harga_beli' => 1200000,
                'harga_jual' => 1500000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 1,
                'barang_kode' => 'BRG005',
                'barang_nama' => 'Headset HyperX Cloud II',
                'harga_beli' => 1000000,
                'harga_jual' => 1200000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Supplier 2
            [
                'kategori_id' => 2,
                'barang_kode' => 'BRG006',
                'barang_nama' => 'Meja Kayu Minimalis',
                'harga_beli' => 700000,
                'harga_jual' => 900000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 2,
                'barang_kode' => 'BRG007',
                'barang_nama' => 'Kursi Kantor Ergonomis',
                'harga_beli' => 1500000,
                'harga_jual' => 1800000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 2,
                'barang_kode' => 'BRG008',
                'barang_nama' => 'Rak Buku Besi',
                'harga_beli' => 500000,
                'harga_jual' => 700000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 2,
                'barang_kode' => 'BRG009',
                'barang_nama' => 'Sofa Minimalis',
                'harga_beli' => 2500000,
                'harga_jual' => 2800000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 2,
                'barang_kode' => 'BRG010',
                'barang_nama' => 'Lemari Pakaian 3 Pintu',
                'harga_beli' => 3000000,
                'harga_jual' => 3500000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Supplier 3
            [
                'kategori_id' => 3,
                'barang_kode' => 'BRG011',
                'barang_nama' => 'Kaos Polos Katun',
                'harga_beli' => 50000,
                'harga_jual' => 80000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 3,
                'barang_kode' => 'BRG012',
                'barang_nama' => 'Jaket Hoodie Unisex',
                'harga_beli' => 150000,
                'harga_jual' => 200000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 3,
                'barang_kode' => 'BRG013',
                'barang_nama' => 'Sepatu Sneakers Casual',
                'harga_beli' => 300000,
                'harga_jual' => 400000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 3,
                'barang_kode' => 'BRG014',
                'barang_nama' => 'Topi Baseball Trendy',
                'harga_beli' => 100000,
                'harga_jual' => 150000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 3,
                'barang_kode' => 'BRG015',
                'barang_nama' => 'Celana Jeans Slim Fit',
                'harga_beli' => 250000,
                'harga_jual' => 320000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 4,
                'barang_kode' => 'BRG016',
                'barang_nama' => 'Keripik Singkong Balado',
                'harga_beli' => 15000,
                'harga_jual' => 25000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 4,
                'barang_kode' => 'BRG017',
                'barang_nama' => 'Cokelat Dark Premium',
                'harga_beli' => 20000,
                'harga_jual' => 35000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Kosmetik (kategori_id = 5)
            [
                'kategori_id' => 5,
                'barang_kode' => 'BRG018',
                'barang_nama' => 'Lipstik Matte Nude',
                'harga_beli' => 50000,
                'harga_jual' => 75000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 5,
                'barang_kode' => 'BRG019',
                'barang_nama' => 'Sabun Muka Herbal',
                'harga_beli' => 30000,
                'harga_jual' => 45000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];  
        DB::table('m_barang')->insert($data);
    }
}
