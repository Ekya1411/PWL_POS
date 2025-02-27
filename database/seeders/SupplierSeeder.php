<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'supplier_kode' => 'SUP001',
                'supplier_nama' => 'PT Sumber Makmur',
                'supplier_alamat' => 'Jl. Raya Industri No. 10, Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_kode' => 'SUP002',
                'supplier_nama' => 'CV Berkah Abadi',
                'supplier_alamat' => 'Jl. Merdeka No. 45, Bandung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_kode' => 'SUP003',
                'supplier_nama' => 'UD Jaya Sentosa',
                'supplier_alamat' => 'Jl. Veteran No. 12, Surabaya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('m_supplier')->insert($data);
    }
}
