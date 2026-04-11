<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Field;

class TestFieldsSeeder extends Seeder
{
    public function run(): void
    {
        $owners = User::where('role', 'owner')
            ->whereDoesntHave('fields')
            ->get();

        $districts = [
            ['province' => 'Hà Nội', 'district' => 'Cầu Giấy', 'ward' => 'Dịch Vọng'],
            ['province' => 'Hà Nội', 'district' => 'Đống Đa', 'ward' => 'Láng Hạ'],
            ['province' => 'Hà Nội', 'district' => 'Hai Bà Trưng', 'ward' => 'Bạch Mai'],
            ['province' => 'Hà Nội', 'district' => 'Hoàn Kiếm', 'ward' => 'Hàng Bài'],
            ['province' => 'Hà Nội', 'district' => 'Thanh Xuân', 'ward' => 'Nhân Chính'],
            ['province' => 'TP. Hồ Chí Minh', 'district' => 'Quận 1', 'ward' => 'Bến Nghé'],
            ['province' => 'TP. Hồ Chí Minh', 'district' => 'Quận 3', 'ward' => 'Võ Thị Sáu'],
            ['province' => 'TP. Hồ Chí Minh', 'district' => 'Bình Thạnh', 'ward' => 'Phường 25'],
            ['province' => 'TP. Hồ Chí Minh', 'district' => 'Gò Vấp', 'ward' => 'Phường 12'],
            ['province' => 'TP. Hồ Chí Minh', 'district' => 'Tân Bình', 'ward' => 'Phường 2'],
            ['province' => 'Đà Nẵng', 'district' => 'Hải Châu', 'ward' => 'Hải Châu 1'],
            ['province' => 'Đà Nẵng', 'district' => 'Thanh Khê', 'ward' => 'Thanh Khê Đông'],
            ['province' => 'Đà Nẵng', 'district' => 'Sơn Trà', 'ward' => 'An Hải Bắc'],
            ['province' => 'Cần Thơ', 'district' => 'Ninh Kiều', 'ward' => 'An Hòa'],
            ['province' => 'Hải Phòng', 'district' => 'Lê Chân', 'ward' => 'An Dương'],
        ];

        $fieldNames = [
            'Sân Bóng Đá', 'Sân Thể Thao', 'Sân Mini', 'Sân Cỏ Nhân Tạo',
            'Sân Bóng Phú', 'Sân Bóng Thành', 'Sân Bóng Hưng', 'Sân Bóng Đức',
            'Sân Bóng Tâm', 'Sân Bóng Vinh',
        ];

        $descriptions = [
            'Sân bóng đá tiêu chuẩn với cỏ nhân tạo chất lượng cao, hệ thống đèn chiếu sáng hiện đại, phù hợp cho các trận đấu buổi tối.',
            'Sân bóng mini được trang bị đầy đủ tiện nghi, có phòng thay đồ, bãi đỗ xe rộng rãi và căng tin phục vụ.',
            'Sân cỏ nhân tạo thế hệ mới, mặt sân bằng phẳng, hệ thống thoát nước tốt, hoạt động cả khi trời mưa.',
            'Sân bóng đá 5 người và 7 người, có huấn luyện viên hỗ trợ, cho thuê theo giờ hoặc theo tháng.',
            'Sân bóng đá trong nhà với mái che kiên cố, không bị ảnh hưởng bởi thời tiết, phù hợp mọi mùa.',
        ];

        $image = 'images/anhbiasanbong.png';

        foreach ($owners as $i => $owner) {
            $loc = $districts[$i % count($districts)];
            $namePrefix = $fieldNames[$i % count($fieldNames)];
            $price = [80000, 100000, 120000, 150000, 180000, 200000][$i % 6];

            Field::create([
                'owner_id'       => $owner->id,
                'name'           => $namePrefix . ' ' . ($owner->name),
                'address'        => ($i + 1) . ' Đường Số ' . (($i % 20) + 1),
                'address_detail' => 'Số ' . (($i % 50) + 1) . ', Ngõ ' . (($i % 10) + 1),
                'province'       => $loc['province'],
                'district'       => $loc['district'],
                'ward'           => $loc['ward'],
                'hotline'        => '09' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'price_per_hour' => $price,
                'description'    => $descriptions[$i % count($descriptions)],
                'image'          => $image,
                'status'         => 'active',
            ]);
        }

        $this->command->info('Đã tạo sân cho ' . $owners->count() . ' owner.');
    }
}
