<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OwnerFieldsSeeder extends Seeder
{
    public function run(): void
    {
        $fields = [
            [
                'owner_id' => 2,
                'name' => 'Sân bóng Thành Công',
                'province' => 'Thành phố Hồ Chí Minh',
                'district' => null,
                'ward' => 'Phường Bến Nghé',
                'address_detail' => '123 Đường Nguyễn Huệ',
                'address' => '123 Đường Nguyễn Huệ, Phường Bến Nghé, Thành phố Hồ Chí Minh',
                'price_per_hour' => 200000,
                'hotline' => '0901234567',
                'description' => 'Sân bóng đá mini chất lượng cao, cỏ nhân tạo mới, đầy đủ tiện nghi',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'owner_id' => 2,
                'name' => 'Sân bóng Phú Nhuận',
                'province' => 'Thành phố Hồ Chí Minh',
                'district' => null,
                'ward' => 'Phường 13',
                'address_detail' => '456 Đường Phan Đăng Lưu',
                'address' => '456 Đường Phan Đăng Lưu, Phường 13, Thành phố Hồ Chí Minh',
                'price_per_hour' => 250000,
                'hotline' => '0902345678',
                'description' => 'Sân bóng 5 người, có mái che, bãi đỗ xe rộng rãi',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'owner_id' => 2,
                'name' => 'Sân bóng Tân Bình',
                'province' => 'Thành phố Hồ Chí Minh',
                'district' => null,
                'ward' => 'Phường 2',
                'address_detail' => '789 Đường Trường Chinh',
                'address' => '789 Đường Trường Chinh, Phường 2, Thành phố Hồ Chí Minh',
                'price_per_hour' => 180000,
                'hotline' => '0903456789',
                'description' => 'Sân bóng đá 7 người, ánh sáng tốt, phù hợp đá tối',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'owner_id' => 2,
                'name' => 'Sân bóng Hà Nội',
                'province' => 'Thành phố Hà Nội',
                'district' => null,
                'ward' => 'Phường Hàng Bài',
                'address_detail' => '15 Phố Tràng Tiền',
                'address' => '15 Phố Tràng Tiền, Phường Hàng Bài, Thành phố Hà Nội',
                'price_per_hour' => 220000,
                'hotline' => '0904567890',
                'description' => 'Sân bóng đá mini trung tâm Hà Nội, tiện lợi giao thông',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('fields')->insert($fields);

        $this->command->info('Đã thêm ' . count($fields) . ' sân bóng mẫu cho chủ sân!');
    }
}
