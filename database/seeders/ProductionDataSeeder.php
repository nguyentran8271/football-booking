<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Field;
use App\Models\Booking;
use Carbon\Carbon;

class ProductionDataSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        $provinces = [
            ['province' => 'Hà Nội', 'district' => 'Cầu Giấy', 'ward' => 'Dịch Vọng'],
            ['province' => 'Hà Nội', 'district' => 'Đống Đa', 'ward' => 'Láng Hạ'],
            ['province' => 'Hà Nội', 'district' => 'Hai Bà Trưng', 'ward' => 'Bạch Mai'],
            ['province' => 'TP. Hồ Chí Minh', 'district' => 'Quận 1', 'ward' => 'Bến Nghé'],
            ['province' => 'TP. Hồ Chí Minh', 'district' => 'Bình Thạnh', 'ward' => 'Phường 25'],
            ['province' => 'TP. Hồ Chí Minh', 'district' => 'Gò Vấp', 'ward' => 'Phường 12'],
            ['province' => 'Đà Nẵng', 'district' => 'Hải Châu', 'ward' => 'Hải Châu 1'],
            ['province' => 'Đà Nẵng', 'district' => 'Thanh Khê', 'ward' => 'Thanh Khê Đông'],
            ['province' => 'Cần Thơ', 'district' => 'Ninh Kiều', 'ward' => 'An Hòa'],
            ['province' => 'Hải Phòng', 'district' => 'Lê Chân', 'ward' => 'An Dương'],
            ['province' => 'Bình Dương', 'district' => 'Thủ Dầu Một', 'ward' => 'Phú Cường'],
            ['province' => 'Đồng Nai', 'district' => 'Biên Hòa', 'ward' => 'Tân Phong'],
            ['province' => 'Khánh Hòa', 'district' => 'Nha Trang', 'ward' => 'Vĩnh Hải'],
            ['province' => 'Thừa Thiên Huế', 'district' => 'TP. Huế', 'ward' => 'Phú Hội'],
            ['province' => 'Quảng Nam', 'district' => 'Hội An', 'ward' => 'Minh An'],
            ['province' => 'Quảng Ngãi', 'district' => 'TP. Quảng Ngãi', 'ward' => 'Nghĩa Lộ'],
            ['province' => 'Bình Định', 'district' => 'Quy Nhơn', 'ward' => 'Lý Thường Kiệt'],
            ['province' => 'Phú Yên', 'district' => 'Tuy Hòa', 'ward' => 'Phú Thạnh'],
            ['province' => 'Lâm Đồng', 'district' => 'Đà Lạt', 'ward' => 'Phường 1'],
            ['province' => 'Bà Rịa - Vũng Tàu', 'district' => 'Vũng Tàu', 'ward' => 'Phường 1'],
            ['province' => 'Long An', 'district' => 'Tân An', 'ward' => 'Phường 1'],
            ['province' => 'Tiền Giang', 'district' => 'Mỹ Tho', 'ward' => 'Phường 1'],
            ['province' => 'Bến Tre', 'district' => 'TP. Bến Tre', 'ward' => 'Phường 1'],
            ['province' => 'Vĩnh Long', 'district' => 'TP. Vĩnh Long', 'ward' => 'Phường 1'],
            ['province' => 'Trà Vinh', 'district' => 'TP. Trà Vinh', 'ward' => 'Phường 1'],
            ['province' => 'Sóc Trăng', 'district' => 'TP. Sóc Trăng', 'ward' => 'Phường 1'],
            ['province' => 'An Giang', 'district' => 'Long Xuyên', 'ward' => 'Mỹ Bình'],
            ['province' => 'Kiên Giang', 'district' => 'Rạch Giá', 'ward' => 'Vĩnh Thanh'],
            ['province' => 'Cà Mau', 'district' => 'TP. Cà Mau', 'ward' => 'Phường 1'],
            ['province' => 'Bạc Liêu', 'district' => 'TP. Bạc Liêu', 'ward' => 'Phường 1'],
            ['province' => 'Hậu Giang', 'district' => 'Vị Thanh', 'ward' => 'Phường 1'],
            ['province' => 'Đồng Tháp', 'district' => 'Cao Lãnh', 'ward' => 'Phường 1'],
            ['province' => 'Nghệ An', 'district' => 'Vinh', 'ward' => 'Hưng Bình'],
            ['province' => 'Thanh Hóa', 'district' => 'TP. Thanh Hóa', 'ward' => 'Điện Biên'],
        ];

        $firstNames = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Huỳnh', 'Phan', 'Vũ', 'Đặng', 'Bùi'];
        $middleNames = ['Văn', 'Thị', 'Hữu', 'Minh', 'Quốc', 'Thanh', 'Đức', 'Anh', 'Thành', 'Bảo'];
        $lastNames = ['An', 'Bình', 'Cường', 'Dũng', 'Phong', 'Giang', 'Hùng', 'Khoa', 'Long', 'Minh',
                      'Nam', 'Phúc', 'Quân', 'Sơn', 'Tâm', 'Uy', 'Vinh', 'Xuân', 'Hải', 'Tuấn'];

        $fieldNames = ['Sân Bóng Đá', 'Sân Thể Thao', 'Sân Mini', 'Sân Cỏ Nhân Tạo', 'Sân Bóng'];
        $descriptions = [
            'Sân bóng đá tiêu chuẩn với cỏ nhân tạo chất lượng cao, hệ thống đèn chiếu sáng hiện đại.',
            'Sân bóng mini được trang bị đầy đủ tiện nghi, có phòng thay đồ và bãi đỗ xe rộng rãi.',
            'Sân cỏ nhân tạo thế hệ mới, mặt sân bằng phẳng, hoạt động cả khi trời mưa.',
            'Sân bóng đá 5 người và 7 người, cho thuê theo giờ hoặc theo tháng.',
        ];

        $image = 'images/anhbiasanbong.png';
        $prices = [80000, 100000, 120000, 150000, 180000, 200000];
        $shifts = [1, 2, 3, 4, 5, 6];

        // Tạo 50 users
        $users = [];
        for ($i = 1; $i <= 50; $i++) {
            $name = $firstNames[array_rand($firstNames)] . ' ' .
                    $middleNames[array_rand($middleNames)] . ' ' .
                    $lastNames[array_rand($lastNames)];
            $users[] = User::create([
                'name'              => $name,
                'email'             => "user{$i}@test.com",
                'password'          => $password,
                'role'              => 'user',
                'phone'             => '09' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'email_verified_at' => now(),
            ]);
        }

        // Tạo 50 owners + mỗi owner 1 sân
        $fields = [];
        for ($i = 1; $i <= 50; $i++) {
            $name = $firstNames[array_rand($firstNames)] . ' ' .
                    $middleNames[array_rand($middleNames)] . ' ' .
                    $lastNames[array_rand($lastNames)];
            $owner = User::create([
                'name'              => $name,
                'email'             => "owner{$i}@test.com",
                'password'          => $password,
                'role'              => 'owner',
                'phone'             => '09' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'email_verified_at' => now(),
            ]);

            $loc = $provinces[$i % count($provinces)];
            $price = $prices[$i % count($prices)];

            $fields[] = Field::create([
                'owner_id'       => $owner->id,
                'name'           => $fieldNames[$i % count($fieldNames)] . ' ' . $name,
                'address'        => ($i + 1) . ' Đường Số ' . (($i % 20) + 1) . ', ' . $loc['ward'] . ', ' . $loc['district'] . ', ' . $loc['province'],
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

        // Tạo bookings - mỗi sân ~10 booking approved trong 3 tháng qua
        $bookingCount = 0;
        foreach ($fields as $field) {
            $numBookings = rand(8, 15);
            $usedSlots = [];

            for ($b = 0; $b < $numBookings; $b++) {
                $daysAgo = rand(1, 90);
                $date = Carbon::now()->subDays($daysAgo)->format('Y-m-d');
                $shift = $shifts[array_rand($shifts)];
                $slotKey = $date . '_' . $shift;

                if (in_array($slotKey, $usedSlots)) continue;
                $usedSlots[] = $slotKey;

                $user = $users[array_rand($users)];

                Booking::create([
                    'user_id'     => $user->id,
                    'field_id'    => $field->id,
                    'date'        => $date,
                    'shift'       => $shift,
                    'total_price' => $field->price_per_hour,
                    'status'      => 'approved',
                    'created_at'  => Carbon::parse($date)->subDays(rand(1, 3)),
                    'updated_at'  => Carbon::parse($date)->subDays(rand(0, 1)),
                ]);
                $bookingCount++;
            }
        }

        $this->command->info("Đã tạo: 50 users, 50 owners, 50 sân, {$bookingCount} bookings.");
    }
}
