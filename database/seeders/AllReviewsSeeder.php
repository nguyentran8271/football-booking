<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Field;

class AllReviewsSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->take(20)->get();
        $fields = Field::all();

        if ($users->isEmpty()) return;

        $fieldComments = [
            'Sân rất đẹp, cỏ nhân tạo chất lượng cao, đèn chiếu sáng tốt. Sẽ quay lại!',
            'Sân sạch sẽ, nhân viên nhiệt tình. Giá cả hợp lý. Recommend!',
            'Mặt sân bằng phẳng, không bị trơn trượt. Phòng thay đồ sạch. 5 sao!',
            'Sân tốt, vị trí thuận tiện. Đặt sân online rất nhanh. Hài lòng!',
            'Cỏ mới, đèn sáng, bãi đỗ xe rộng. Giá hơi cao nhưng xứng đáng.',
            'Sân đẹp, nhân viên thân thiện. Sẽ giới thiệu cho bạn bè.',
            'Chất lượng sân tốt, vệ sinh sạch sẽ. Đặt sân dễ dàng qua web.',
            'Sân rộng, thoáng mát. Có căng tin phục vụ. Rất hài lòng!',
            'Mặt sân tốt, không bị lồi lõm. Nhân viên hỗ trợ nhiệt tình.',
            'Sân đẹp, giá hợp lý. Hệ thống đặt sân online rất tiện lợi.',
        ];

        $webComments = [
            'Website dễ sử dụng, đặt sân nhanh chóng. Rất hài lòng!',
            'Giao diện đẹp, tìm sân dễ dàng. Hỗ trợ khách hàng tốt.',
            'Hệ thống đặt sân online tiện lợi, không cần gọi điện. Tuyệt vời!',
            'Website chuyên nghiệp, tính năng đầy đủ. 5 sao!',
            'Đặt sân chỉ vài click, rất tiện. Giao diện thân thiện.',
            'Tìm sân gần nhà dễ dàng. Giá cả minh bạch. Recommend!',
            'Website tốt, tốc độ nhanh. Quy trình đặt sân đơn giản.',
            'Hài lòng với dịch vụ. Sẽ tiếp tục sử dụng.',
            'Giao diện chuyên nghiệp, tính năng đầy đủ. Quản lý booking dễ dàng.',
            'Rất hài lòng! Website chuyên nghiệp. Hỗ trợ 24/7 tuyệt vời!',
        ];

        $count = 0;

        // Review cho từng sân - tăng lên 15-20 review/sân
        foreach ($fields as $field) {
            $numReviews = rand(15, 20);
            $usedUsers = [];
            $allUsers = User::where('role', 'user')->get();
            for ($i = 0; $i < $numReviews; $i++) {
                $user = $allUsers->random();
                if (in_array($user->id, $usedUsers)) continue;
                $usedUsers[] = $user->id;
                $rating = rand(3, 5);
                Review::create([
                    'user_id'              => $user->id,
                    'field_id'             => $field->id,
                    'rating'               => $rating,
                    'field_quality_rating' => min(5, round($rating - 0.5 + (rand(0, 10) / 10), 1)),
                    'lighting_rating'      => min(5, round($rating - 0.5 + (rand(0, 10) / 10), 1)),
                    'hygiene_rating'       => min(5, round($rating - 0.5 + (rand(0, 10) / 10), 1)),
                    'staff_rating'         => min(5, round($rating + (rand(0, 5) / 10), 1)),
                    'price_rating'         => min(5, round($rating - 0.5 + (rand(0, 10) / 10), 1)),
                    'comment'              => $fieldComments[array_rand($fieldComments)],
                    'helpful_count'        => rand(0, 30),
                    'created_at'           => now()->subDays(rand(1, 180)),
                ]);
                $count++;
            }
        }

        // Review website
        foreach ($webComments as $comment) {
            $user = $users->random();
            $rating = rand(4, 5);
            Review::create([
                'user_id'              => $user->id,
                'field_id'             => null,
                'rating'               => $rating,
                'field_quality_rating' => min(5, round($rating - 0.3 + (rand(0, 5) / 10), 1)),
                'lighting_rating'      => min(5, round($rating - 0.3 + (rand(0, 5) / 10), 1)),
                'hygiene_rating'       => min(5, round($rating - 0.3 + (rand(0, 5) / 10), 1)),
                'staff_rating'         => min(5, round($rating + (rand(0, 3) / 10), 1)),
                'price_rating'         => min(5, round($rating - 0.3 + (rand(0, 5) / 10), 1)),
                'comment'              => $comment,
                'helpful_count'        => rand(5, 50),
                'created_at'           => now()->subDays(rand(1, 90)),
            ]);
            $count++;
        }

        $this->command->info("Đã tạo {$count} đánh giá.");
    }
}
