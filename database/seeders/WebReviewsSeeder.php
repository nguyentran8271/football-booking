<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;

class WebReviewsSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::pluck('id')->toArray();

        // Batch 2 — thêm 10 review mới (không check trùng)
        $extra = [
            [5, 5, 5, 4, 5, 5, 'Trang web load rất nhanh, tìm sân dễ dàng. Rất hài lòng!'],
            [5, 4, 5, 5, 5, 4, 'Giao diện hiện đại, thao tác đặt sân đơn giản. Tuyệt vời!'],
            [4, 4, 4, 4, 4, 3, 'Hệ thống ổn định, ít gặp lỗi. Sẽ giới thiệu cho bạn bè.'],
            [5, 5, 4, 5, 5, 5, 'Thông tin sân chi tiết, có ảnh rõ ràng. Rất tiện để chọn sân.'],
            [4, 3, 4, 4, 5, 4, 'Đặt sân nhanh chóng, xác nhận tức thì. Dịch vụ tốt!'],
            [5, 5, 5, 5, 4, 5, 'Website dễ dùng trên điện thoại. Responsive rất tốt.'],
            [4, 4, 3, 4, 4, 4, 'Tính năng lọc sân theo khu vực rất hữu ích. Cảm ơn team!'],
            [5, 5, 5, 5, 5, 5, 'Hỗ trợ khách hàng nhiệt tình, giải đáp nhanh. 5 sao!'],
            [4, 4, 4, 3, 4, 4, 'Giao diện đẹp mắt, màu sắc hài hòa. Trải nghiệm tốt.'],
            [5, 5, 5, 5, 4, 5, 'Chức năng xem lịch sử đặt sân rất tiện. Dùng mãi không chán.'],
        ];

        foreach ($extra as $i => $row) {
            Review::create([
                'user_id'              => $users[$i % count($users)],
                'field_id'             => null,
                'rating'               => $row[0],
                'field_quality_rating' => $row[1],
                'lighting_rating'      => $row[2],
                'hygiene_rating'       => $row[3],
                'staff_rating'         => $row[4],
                'price_rating'         => $row[5],
                'comment'              => $row[6],
                'helpful_count'        => rand(0, 20),
            ]);
        }

        $reviews = [
            [
                'rating' => 5,
                'field_quality_rating' => 5,
                'lighting_rating' => 5,
                'hygiene_rating' => 4,
                'staff_rating' => 5,
                'price_rating' => 5,
                'comment' => 'Website rất dễ sử dụng, giao diện đẹp và tốc độ tải nhanh. Tôi đặt sân chỉ mất vài phút!',
            ],
            [
                'rating' => 4,
                'field_quality_rating' => 4,
                'lighting_rating' => 4,
                'hygiene_rating' => 5,
                'staff_rating' => 4,
                'price_rating' => 3,
                'comment' => 'Tính năng đặt sân tiện lợi, hỗ trợ khách hàng phản hồi nhanh. Giao diện khá thân thiện.',
            ],
            [
                'rating' => 5,
                'field_quality_rating' => 5,
                'lighting_rating' => 4,
                'hygiene_rating' => 5,
                'staff_rating' => 5,
                'price_rating' => 4,
                'comment' => 'Trải nghiệm tuyệt vời! Tìm sân và đặt lịch rất nhanh chóng. Sẽ tiếp tục sử dụng.',
            ],
            [
                'rating' => 4,
                'field_quality_rating' => 3,
                'lighting_rating' => 4,
                'hygiene_rating' => 4,
                'staff_rating' => 5,
                'price_rating' => 4,
                'comment' => 'Ứng dụng hoạt động ổn định, ít lỗi. Mong có thêm tính năng lọc sân theo giá.',
            ],
            [
                'rating' => 5,
                'field_quality_rating' => 5,
                'lighting_rating' => 5,
                'hygiene_rating' => 5,
                'staff_rating' => 4,
                'price_rating' => 5,
                'comment' => 'Rất hài lòng với website. Thông tin sân đầy đủ, hình ảnh rõ ràng, đặt sân dễ dàng.',
            ],
            [
                'rating' => 3,
                'field_quality_rating' => 3,
                'lighting_rating' => 3,
                'hygiene_rating' => 4,
                'staff_rating' => 3,
                'price_rating' => 3,
                'comment' => 'Website dùng được nhưng đôi khi hơi chậm. Cần cải thiện thêm phần tìm kiếm.',
            ],
            [
                'rating' => 5,
                'field_quality_rating' => 5,
                'lighting_rating' => 5,
                'hygiene_rating' => 5,
                'staff_rating' => 5,
                'price_rating' => 5,
                'comment' => 'Tuyệt vời! Đây là website đặt sân bóng tốt nhất tôi từng dùng. Rất tiện lợi và chuyên nghiệp.',
            ],
            [
                'rating' => 4,
                'field_quality_rating' => 4,
                'lighting_rating' => 5,
                'hygiene_rating' => 4,
                'staff_rating' => 4,
                'price_rating' => 4,
                'comment' => 'Giao diện đẹp, dễ điều hướng. Tính năng xem lịch sử đặt sân rất hữu ích.',
            ],
        ];

        foreach ($reviews as $i => $data) {
            $userId = $users[$i % count($users)];

            // Bỏ qua nếu user này đã có web review
            $exists = Review::where('user_id', $userId)->whereNull('field_id')->exists();
            if ($exists) continue;

            Review::create(array_merge($data, [
                'user_id'      => $userId,
                'field_id'     => null,
                'helpful_count' => rand(0, 15),
            ]));
        }
    }
}
