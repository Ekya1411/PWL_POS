<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kategori_kode' => 'ELK001',
                'kategori_nama' => 'Elektronik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_kode' => 'FRN002',
                'kategori_nama' => 'Furniture',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_kode' => 'FAS003',
                'kategori_nama' => 'Fashion',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_kode' => 'MKN004',
                'kategori_nama' => 'Makanan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_kode' => 'KSR005',
                'kategori_nama' => 'Kosmetik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('m_kategori')->insert($data);
    }
}
