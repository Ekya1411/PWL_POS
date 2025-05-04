<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'user_id' => 1,
                'level_id' => 1,
                'username' => 'admin',
                'nama' => 'Administrator',
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 2,
                'level_id' => 2,
                'username' => 'manager',
                'nama' => 'Agus Santoso',
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 3,
                'level_id' => 3,
                'username' => 'staff1',
                'nama' => 'Dina Maharani',
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 4,
                'level_id' => 3,
                'username' => 'staff2',
                'nama' => 'Rudi Hartono',
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 5,
                'level_id' => 3,
                'username' => 'staff3',
                'nama' => 'Siti Aisyah',
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 6,
                'level_id' => 3,
                'username' => 'staff4',
                'nama' => 'Budi Prasetyo',
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 7,
                'level_id' => 3,
                'username' => 'staff5',
                'nama' => 'Intan Permata',
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 8,
                'level_id' => 3,
                'username' => 'staff6',
                'nama' => 'Rizki Aditya',
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 9,
                'level_id' => 3,
                'username' => 'staff7',
                'nama' => 'Yulia Fitriani',
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 10,
                'level_id' => 3,
                'username' => 'staff8',
                'nama' => 'Fajar Nugroho',
                'password' => Hash::make('12345'),
            ],
        ];

        DB::table('m_user')->insert($data);
    }
}
