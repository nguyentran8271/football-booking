<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Field;

class DetailedReviewsSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $fields = Field::all();

        if ($users->isEmpty() || $fields->isEmpty()) {
            $this->command->warn('Cần có users và fields trước khi chạy seeder này!');
            return;
        }

        $reviews = [
            [
                'rating' => 5,
                'field_quality_rating' => 4.8,
                'lighting_rating' => 5.0,
                'hygiene_rating' => 4.5,
                'staff_rating' => 5.0,
                'price_rating' => 4.0,
                'comment' => 'Website rất dễ sử dụng, giao diện đẹp mắt. Đặt sân nhanh chóng, không mất nhiều thời gian. Hỗ trợ khách hàng nhiệt tình. Rất hài lòng!',
            ],
            [
                'rating' => 5,
                'field_quality_rating' => 5.0,
                'lighting_rating' => 4.8,
                'hygiene_rating' => 5.0,
                'staff_rating' => 4.8,
                'price_rating' => 4.5,
                'comment' => 'Giao diện chuyên nghiệp, tính năng đầy đủ. Tốc độ tải trang nhanh. Quy trình đặt sân rất đơn giản. Tôi là chủ sân và rất hài lòng với hệ thống quản lý.',
            ],
            [
                'rating' => 4,
                'field_quality_rating' => 4.0,
                'lighting_rating' => 4.5,
                'hygiene_rating' => 4.0,
                'staff_rating' => 4.0,
                'price_rating' => 4.5,
                'comment' => 'Website tốt, dễ tìm kiếm sân. Thanh toán thuận tiện. Có thể cải thiện thêm tính năng thông báo. Nhìn chung rất ổn!',
            ],
            [
                'rating' => 5,
                'field_quality_rating' => 4.9,
                'lighting_rating' => 4.7,
                'hygiene_rating' => 4.8,
                'staff_rating' => 5.0,
                'price_rating' => 4.3,
                'comment' => 'Tuyệt vời! Đặt sân online rất tiện lợi. Không cần gọi điện, mọi thứ đều có trên web. Hỗ trợ 24/7 rất tốt.',
            ],
            [
                'rating' => 4,
                'field_quality_rating' => 4.2,
                'lighting_rating' => 4.0,
                'hygiene_rating' => 4.3,
                'staff_rating' => 4.5,
                'price_rating' => 4.0,
                'comment' => 'Giao diện thân thiện, dễ sử dụng ngay cả với người lớn tuổi. Tính năng lịch sử đặt sân rất hữu ích. Recommend!',
            ],
            [
                'rating' => 5,
                'field_quality_rating' => 4.8,
                'lighting_rating' => 5.0,
                'hygiene_rating' => 4.8,
                'staff_rating' => 5.0,
                'price_rating' => 4.0,
                'comment' => 'Hệ thống đặt sân tuyệt vời! Giao diện đẹp, tính năng đầy đủ. Quản lý booking rất tiện. Là chủ sân tôi rất hài lòng!',
            ],
            [
                'rating' => 4,
                'field_quality_rating' => 4.5,
                'lighting_rating' => 4.0,
                'hygiene_rating' => 4.5,
                'staff_rating' => 4.5,
                'price_rating' => 4.8,
                'comment' => 'Website dễ dùng, tìm sân nhanh. Giá cả minh bạch. Tốc độ load hơi chậm đôi lúc nhưng chấp nhận được.',
            ],
            [
                'rating' => 5,
                'field_quality_rating' => 5.0,
                'lighting_rating' => 5.0,
                'hygiene_rating' => 5.0,
                'staff_rating' => 5.0,
                'price_rating' => 5.0,
                'comment' => 'Hoàn hảo! Mọi thứ đều tốt. Giao diện đẹp, tính năng mạnh, hỗ trợ tốt. Đây là website đặt sân tốt nhất tôi từng dùng!',
            ],
            [
                'rating' => 4,
                'field_quality_rating' => 4.3,
                'lighting_rating' => 4.2,
                'hygiene_rating' => 4.0,
                'staff_rating' => 4.5,
                'price_rating' => 4.0,
                'comment' => 'Website ổn, đặt sân dễ dàng. Có thể thêm tính năng đánh giá sân sau khi đá. Nhìn chung tốt!',
            ],
            [
                'rating' => 5,
                'field_quality_rating' => 4.7,
                'lighting_rating' => 4.8,
                'hygiene_rating' => 4.9,
                'staff_rating' => 5.0,
                'price_rating' => 4.5,
                'comment' => 'Rất hài lòng với website. Giao diện chuyên nghiệp, tính năng đầy đủ. Hỗ trợ khách hàng nhanh chóng. 5 sao!',
            ],
            [
                'rating' => 3,
                'field_quality_rating' => 3.5,
                'lighting_rating' => 3.0,
                'hygiene_rating' => 3.5,
                'staff_rating' => 3.5,
                'price_rating' => 4.0,
                'comment' => 'Website tạm ổn. Giao diện cần cải thiện thêm. Tính năng cơ bản đầy đủ nhưng chưa thực sự nổi bật.',
            ],
            [
                'rating' => 5,
                'field_quality_rating' => 4.9,
                'lighting_rating' => 4.8,
                'hygiene_rating' => 5.0,
                'staff_rating' => 5.0,
                'price_rating' => 4.7,
                'comment' => 'Website tuyệt vời! Đặt sân nhanh, thanh toán dễ dàng. Hệ thống thông báo rất tiện. Highly recommended!',
            ],
            [
                'rating' => 4,
                'field_quality_rating' => 4.0,
                'lighting_rating' => 4.3,
                'hygiene_rating' => 4.2,
                'staff_rating' => 4.0,
                'price_rating' => 4.5,
                'comment' => 'Giao diện đẹp, dễ sử dụng. Tính năng tìm kiếm sân rất tốt. Có thể thêm filter theo giá. Overall good!',
            ],
            [
                'rating' => 5,
                'field_quality_rating' => 5.0,
                'lighting_rating' => 4.9,
                'hygiene_rating' => 4.8,
                'staff_rating' => 5.0,
                'price_rating' => 4.5,
                'comment' => 'Là chủ sân, tôi rất hài lòng với hệ thống quản lý. Dễ dàng theo dõi booking, doanh thu. Giao diện admin rất trực quan!',
            ],
            [
                'rating' => 4,
                'field_quality_rating' => 4.5,
                'lighting_rating' => 4.0,
                'hygiene_rating' => 4.3,
                'staff_rating' => 4.5,
                'price_rating' => 4.0,
                'comment' => 'Website tốt, đặt sân thuận tiện. Tốc độ nhanh. Có thể thêm tính năng chat với chủ sân. Recommend!',
            ],
            [
                'rating' => 5,
                'field_quality_rating' => 4.8,
                'lighting_rating' => 5.0,
                'hygiene_rating' => 4.7,
                'staff_rating' => 5.0,
                'price_rating' => 4.8,
                'comment' => 'Tuyệt vời! Giao diện đẹp, tính năng mạnh. Đặt sân chỉ mất vài phút. Hỗ trợ khách hàng rất tốt. 5 sao!',
            ],
            [
                'rating' => 3,
                'field_quality_rating' => 3.0,
                'lighting_rating' => 3.5,
                'hygiene_rating' => 3.0,
                'staff_rating' => 3.5,
                'price_rating' => 4.0,
                'comment' => 'Website bình thường. Tính năng cơ bản có đủ nhưng giao diện chưa thực sự bắt mắt. Cần cải thiện UX.',
            ],
            [
                'rating' => 5,
                'field_quality_rating' => 5.0,
                'lighting_rating' => 4.8,
                'hygiene_rating' => 5.0,
                'staff_rating' => 5.0,
                'price_rating' => 4.5,
                'comment' => 'Website số 1! Mọi thứ đều hoàn hảo. Giao diện đẹp, tính năng đầy đủ, hỗ trợ tốt. Không có gì để chê!',
            ],
            [
                'rating' => 4,
                'field_quality_rating' => 4.2,
                'lighting_rating' => 4.5,
                'hygiene_rating' => 4.0,
                'staff_rating' => 4.3,
                'price_rating' => 4.5,
                'comment' => 'Dễ sử dụng, tìm sân nhanh. Thanh toán an toàn. Có thể thêm tính năng đặt sân định kỳ. Tốt!',
            ],
            [
                'rating' => 5,
                'field_quality_rating' => 4.9,
                'lighting_rating' => 4.7,
                'hygiene_rating' => 4.8,
                'staff_rating' => 5.0,
                'price_rating' => 4.6,
                'comment' => 'Rất hài lòng! Website chuyên nghiệp, tính năng đầy đủ. Quản lý booking dễ dàng. Hỗ trợ 24/7 tuyệt vời!',
            ],
        ];

        foreach ($reviews as $reviewData) {
            Review::create([
                'user_id' => $users->random()->id,
                'field_id' => $fields->random()->id,
                'rating' => $reviewData['rating'],
                'field_quality_rating' => $reviewData['field_quality_rating'],
                'lighting_rating' => $reviewData['lighting_rating'],
                'hygiene_rating' => $reviewData['hygiene_rating'],
                'staff_rating' => $reviewData['staff_rating'],
                'price_rating' => $reviewData['price_rating'],
                'comment' => $reviewData['comment'],
                'location' => null,
                'helpful_count' => rand(0, 50),
            ]);
        }

        $this->command->info('Đã tạo ' . count($reviews) . ' đánh giá về website!');
    }
}
