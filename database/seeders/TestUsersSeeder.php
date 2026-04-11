<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        $firstNames = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Huỳnh', 'Phan', 'Vũ', 'Đặng', 'Bùi'];
        $middleNames = ['Văn', 'Thị', 'Hữu', 'Minh', 'Quốc', 'Thanh', 'Đức', 'Anh', 'Thành', 'Bảo'];
        $lastNames = ['An', 'Bình', 'Cường', 'Dũng', 'Em', 'Phong', 'Giang', 'Hùng', 'Khoa', 'Long',
                      'Minh', 'Nam', 'Oanh', 'Phúc', 'Quân', 'Sơn', 'Tâm', 'Uy', 'Vinh', 'Xuân'];

        $password = Hash::make('password');

        // 50 users
        for ($i = 1; $i <= 50; $i++) {
            $name = $firstNames[array_rand($firstNames)] . ' ' .
                    $middleNames[array_rand($middleNames)] . ' ' .
                    $lastNames[array_rand($lastNames)];

            User::create([
                'name'              => $name,
                'email' => "user{$i}@gmail.com",
                'password'          => $password,
                'role'              => 'user',
                'phone'             => '09' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'email_verified_at' => now(),
            ]);
        }

        // 50 owners
        for ($i = 1; $i <= 50; $i++) {
            $name = $firstNames[array_rand($firstNames)] . ' ' .
                    $middleNames[array_rand($middleNames)] . ' ' .
                    $lastNames[array_rand($lastNames)];

            User::create([
                'name'              => $name,
                'email' => "owner{$i}@gmail.com",
                'password'          => $password,
                'role'              => 'owner',
                'phone'             => '09' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'email_verified_at' => now(),
            ]);
        }
    }
}
