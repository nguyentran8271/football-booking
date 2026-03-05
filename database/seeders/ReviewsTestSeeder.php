<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Field;
use Illuminate\Support\Facades\DB;

class ReviewsTestSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy users và fields
        $users = User::all();
        $fields = Field::all();

        if ($users->isEmpty() || $fields->isEmpty()) {
            $this->command->warn('Cần có users và fields trong database trước!');
            return;
        }

        $comments = [
            'Website rất dễ sử dụng, đặt sân nhanh chóng và tiện lợi. Giao diện đẹp mắt, thông tin rõ ràng. Rất hài lòng với trải nghiệm!',
            'Trang web thiết kế chuyên nghiệp, tìm kiếm sân dễ dàng. Quy trình đặt sân đơn giản, thanh toán nhanh. Sẽ tiếp tục sử dụng!',
            'Giao diện thân thiện, dễ thao tác. Thông tin sân bóng đầy đủ, hình ảnh rõ nét. Hệ thống đặt sân hoạt động tốt.',
            'Website load nhanh, không bị lag. Tính năng tìm kiếm và lọc sân rất tiện. Hỗ trợ khách hàng nhiệt tình.',
            'Trải nghiệm đặt sân tuyệt vời! Giao diện hiện đại, thông tin chi tiết. Quy trình thanh toán an toàn và nhanh chóng.',
            'Rất ấn tượng với thiết kế website. Dễ dàng tìm được sân phù hợp. Hệ thống thông báo đặt sân rất nhanh.',
            'Website chuyên nghiệp, đầy đủ tính năng. Đặt sân online tiện lợi, không cần gọi điện. Giá cả minh bạch.',
            'Giao diện đẹp, sử dụng mượt mà. Thông tin sân cập nhật liên tục. Rất hài lòng với dịch vụ!',
            'Trang web tốt, nhưng đôi khi hơi chậm vào giờ cao điểm. Nhìn chung vẫn rất hài lòng với trải nghiệm.',
            'Website tiện lợi, giúp tiết kiệm thời gian đặt sân. Giao diện trực quan, dễ hiểu. Sẽ giới thiệu cho bạn bè!',
            'Hệ thống đặt sân online tuyệt vời! Không cần đến trực tiếp, mọi thứ đều online. Rất tiện lợi cho người bận rộn.',
            'Website có đầy đủ thông tin cần thiết. Hình ảnh sân đẹp, mô tả chi tiết. Quy trình đặt sân đơn giản.',
            'Trải nghiệm người dùng tốt, giao diện responsive trên mobile. Đặt sân mọi lúc mọi nơi rất thuận tiện.',
            'Trang web chuyên nghiệp, đáng tin cậy. Hỗ trợ nhiều phương thức thanh toán. Dịch vụ khách hàng tốt.',
            'Website dễ sử dụng, thông tin rõ ràng. Tính năng đánh giá và review giúp chọn sân tốt hơn.',
            'Giao diện đẹp mắt, hiện đại. Tốc độ load trang nhanh. Hệ thống đặt sân hoạt động ổn định.',
            'Rất hài lòng với website! Tìm kiếm sân dễ dàng, đặt sân nhanh chóng. Giá cả hợp lý.',
            'Website thiết kế tốt, thông tin đầy đủ. Quy trình đặt sân rõ ràng. Sẽ tiếp tục ủng hộ!',
            'Trải nghiệm tuyệt vời! Giao diện thân thiện, dễ sử dụng. Hệ thống thông báo nhanh và chính xác.',
            'Website chất lượng, đáp ứng tốt nhu cầu đặt sân. Giao diện đẹp, tính năng đầy đủ. Rất recommend!',
        ];

        $names = [
            'Nguyễn Văn An', 'Trần Thị Bình', 'Lê Hoàng Cường', 'Phạm Thị Dung', 'Hoàng Văn Em',
            'Vũ Thị Phương', 'Đặng Văn Giang', 'Bùi Thị Hà', 'Ngô Văn Hùng', 'Dương Thị Lan',
            'Phan Văn Khoa', 'Mai Thị Linh', 'Tô Văn Minh', 'Lý Thị Nga', 'Đinh Văn Oanh',
            'Võ Thị Phúc', 'Trương Văn Quân', 'Đỗ Thị Rạng', 'Lưu Văn Sơn', 'Hồ Thị Tâm',
        ];

        // Tạo 20 reviews với dữ liệu đa dạng
        for ($i = 0; $i < 20; $i++) {
            $user = $users->random();
            $field = $fields->random();
            $rating = rand(3, 5); // Rating từ 3-5 sao

            // Tạo detailed ratings dựa trên rating chính
            $baseRating = $rating;
            $fieldQuality = $baseRating + (rand(-2, 2) / 10);
            $lighting = $baseRating + (rand(-2, 2) / 10);
            $hygiene = $baseRating + (rand(-2, 2) / 10);
            $staff = $baseRating + (rand(-2, 2) / 10);
            $price = $baseRating + (rand(-2, 2) / 10);

            // Đảm bảo ratings trong khoảng 1-5
            $fieldQuality = max(1, min(5, $fieldQuality));
            $lighting = max(1, min(5, $lighting));
            $hygiene = max(1, min(5, $hygiene));
            $staff = max(1, min(5, $staff));
            $price = max(1, min(5, $price));

            Review::create([
                'user_id' => $user->id,
                'field_id' => $field->id,
                'rating' => $rating,
                'comment' => $comments[$i],
                'field_quality_rating' => round($fieldQuality, 1),
                'lighting_rating' => round($lighting, 1),
                'hygiene_rating' => round($hygiene, 1),
                'staff_rating' => round($staff, 1),
                'price_rating' => round($price, 1),
                'helpful_count' => rand(0, 50),
                'location' => null,
                'images' => null,
                'created_at' => now()->subDays(rand(1, 90)),
                'updated_at' => now()->subDays(rand(1, 90)),
            ]);
        }

        $this->command->info('Đã tạo 20 reviews test thành công!');
    }
}
