<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo admin mặc định
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        // Tạo owner mẫu
        User::create([
            'name' => 'Chủ sân mẫu',
            'email' => 'owner@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'owner',
        ]);

        // Tạo user mẫu
        User::create([
            'name' => 'Người dùng mẫu',
            'email' => 'user@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'user',
        ]);
    }
}
