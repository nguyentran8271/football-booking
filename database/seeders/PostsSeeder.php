<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostsSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Top 5 sân bóng đá chất lượng nhất tại Hà Nội năm 2026',
                'category' => 'trong_nuoc',
                'content' => 'Hà Nội hiện có hàng trăm sân bóng đá lớn nhỏ, nhưng không phải sân nào cũng đảm bảo chất lượng. Dưới đây là danh sách 5 sân bóng được đánh giá cao nhất về mặt bằng, ánh sáng và dịch vụ hỗ trợ.',
                'image' => null,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'title' => 'Hướng dẫn chọn giày đá bóng phù hợp với từng loại sân',
                'category' => 'trong_nuoc',
                'content' => 'Việc chọn đúng loại giày đá bóng không chỉ ảnh hưởng đến hiệu suất thi đấu mà còn giúp bảo vệ đôi chân của bạn. Sân cỏ tự nhiên, sân cỏ nhân tạo và sân futsal đều yêu cầu loại đế giày khác nhau.',
                'image' => null,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'title' => 'Lịch thi đấu giải bóng đá phong trào mùa xuân 2026',
                'category' => 'trong_nuoc',
                'content' => 'Giải bóng đá phong trào mùa xuân 2026 chính thức khởi tranh với sự tham gia của hơn 32 đội bóng đến từ các quận huyện. Các trận đấu sẽ diễn ra vào cuối tuần tại các sân bóng đã đăng ký trên hệ thống.',
                'image' => null,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'title' => 'Bí quyết tập luyện thể lực cho cầu thủ nghiệp dư',
                'category' => 'trong_nuoc',
                'content' => 'Thể lực tốt là nền tảng để thi đấu hiệu quả trong suốt 90 phút. Bài viết chia sẻ các bài tập cardio, sức mạnh và linh hoạt phù hợp cho cầu thủ không chuyên, có thể thực hiện ngay tại nhà hoặc tại sân.',
                'image' => null,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'title' => 'Xu hướng sân bóng cỏ nhân tạo thế hệ mới tại Việt Nam',
                'category' => 'trong_nuoc',
                'content' => 'Cỏ nhân tạo thế hệ thứ 4 đang dần thay thế các loại cỏ cũ tại nhiều sân bóng Việt Nam. Với độ bền cao, khả năng thoát nước tốt và cảm giác chân gần giống cỏ tự nhiên, đây là lựa chọn được nhiều chủ sân ưu tiên đầu tư.',
                'image' => null,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'title' => 'Cách đặt sân bóng trực tuyến nhanh chóng và tiết kiệm',
                'category' => 'trong_nuoc',
                'content' => 'Đặt sân bóng trực tuyến giúp bạn tiết kiệm thời gian và tránh tình trạng sân đã được đặt trước. Chỉ cần chọn sân, chọn khung giờ và xác nhận thanh toán, bạn đã có thể yên tâm chuẩn bị cho trận đấu.',
                'image' => null,
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(6),
            ],
            [
                'title' => 'Những lỗi thường gặp khi đặt sân và cách tránh',
                'category' => 'ngoai_nuoc',
                'content' => 'Nhiều người dùng gặp phải tình trạng đặt nhầm giờ, nhầm sân hoặc quên hủy đặt chỗ. Bài viết tổng hợp các lỗi phổ biến và hướng dẫn cách xử lý để có trải nghiệm đặt sân suôn sẻ nhất.',
                'image' => null,
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'title' => 'Review sân bóng mini tại quận Cầu Giấy - Hà Nội',
                'category' => 'trong_nuoc',
                'content' => 'Quận Cầu Giấy tập trung nhiều sân bóng mini chất lượng với giá cả phải chăng. Bài review chi tiết về mặt bằng, ánh sáng, giá thuê và dịch vụ đi kèm tại 3 sân nổi bật nhất khu vực này.',
                'image' => null,
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(8),
            ],
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }
    }
}
