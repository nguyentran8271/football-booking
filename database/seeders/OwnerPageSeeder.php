<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OwnerStat;
use App\Models\OwnerBenefit;
use App\Models\OwnerStep;
use App\Models\OwnerSection;

class OwnerPageSeeder extends Seeder
{
    public function run(): void
    {
        // Stats (không có ảnh)
        $stats = [
            ['number' => '500+', 'label' => 'Chủ sân đối tác', 'order' => 1],
            ['number' => '10,000+', 'label' => 'Booking mỗi tháng', 'order' => 2],
            ['number' => '95%', 'label' => 'Tỷ lệ hài lòng', 'order' => 3],
            ['number' => '24/7', 'label' => 'Hỗ trợ khách hàng', 'order' => 4],
        ];

        foreach ($stats as $stat) {
            OwnerStat::create($stat);
        }

        // Benefits
        $benefits = [
            [
                'title' => 'Tăng Doanh Thu',
                'description' => 'Tiếp cận hàng nghìn khách hàng tiềm năng đang tìm kiếm sân bóng chất lượng mỗi ngày',
                'order' => 1,
            ],
            [
                'title' => 'Quản Lý Dễ Dàng',
                'description' => 'Hệ thống quản lý booking tự động, theo dõi lịch đặt sân và doanh thu realtime',
                'order' => 2,
            ],
            [
                'title' => 'Miễn Phí Đăng Ký',
                'description' => 'Không mất phí khi tham gia, chỉ trả phí khi có giao dịch thành công',
                'order' => 3,
            ],
            [
                'title' => 'Đa Nền Tảng',
                'description' => 'Quản lý sân bóng mọi lúc mọi nơi trên web và mobile, cập nhật tức thì',
                'order' => 4,
            ],
            [
                'title' => 'Marketing Miễn Phí',
                'description' => 'Sân của bạn được quảng bá rộng rãi trên nền tảng với hàng nghìn người dùng',
                'order' => 5,
            ],
            [
                'title' => 'Thanh Toán An Toàn',
                'description' => 'Hệ thống thanh toán bảo mật, đảm bảo quyền lợi cho cả chủ sân và khách hàng',
                'order' => 6,
            ],
        ];

        foreach ($benefits as $benefit) {
            OwnerBenefit::create($benefit);
        }

        // Steps
        $steps = [
            [
                'step_number' => 1,
                'title' => 'Đăng Ký Tài Khoản',
                'description' => 'Tạo tài khoản miễn phí trên hệ thống chỉ trong vài phút',
            ],
            [
                'step_number' => 2,
                'title' => 'Xác Thực Chủ Sân',
                'description' => 'Liên hệ admin để được xác thực và nâng cấp quyền chủ sân',
            ],
            [
                'step_number' => 3,
                'title' => 'Thêm Thông Tin Sân',
                'description' => 'Upload ảnh, mô tả chi tiết và thiết lập giá cho sân bóng của bạn',
            ],
            [
                'step_number' => 4,
                'title' => 'Nhận Booking',
                'description' => 'Bắt đầu nhận đặt sân từ khách hàng và tăng trưởng doanh thu',
            ],
        ];

        foreach ($steps as $step) {
            OwnerStep::create($step);
        }

        // Sections
        $sections = [
            [
                'title' => 'Tăng Trưởng Doanh Thu Bền Vững',
                'content' => 'Với hệ thống đặt sân trực tuyến hiện đại, chủ sân có thể tiếp cận hàng nghìn khách hàng tiềm năng mỗi ngày. Không còn lo lắng về sân trống, mọi khung giờ đều được tối ưu hóa để mang lại doanh thu cao nhất.',
                'image_position' => 'right',
                'order' => 1,
            ],
            [
                'title' => 'Quản Lý Thông Minh, Tiết Kiệm Thời Gian',
                'content' => 'Dashboard quản lý trực quan giúp bạn theo dõi tất cả booking, doanh thu, và thống kê chi tiết chỉ trong một nơi. Tự động hóa quy trình đặt sân, giảm thiểu công việc thủ công và tập trung vào phát triển kinh doanh.',
                'image_position' => 'left',
                'order' => 2,
            ],
            [
                'title' => 'Hỗ Trợ 24/7, Luôn Đồng Hành',
                'content' => 'Đội ngũ hỗ trợ chuyên nghiệp sẵn sàng giải đáp mọi thắc mắc và hỗ trợ kỹ thuật bất cứ lúc nào. Chúng tôi cam kết mang đến trải nghiệm tốt nhất cho đối tác của mình.',
                'image_position' => 'right',
                'order' => 3,
            ],
        ];

        foreach ($sections as $section) {
            OwnerSection::create($section);
        }

        $this->command->info('Đã tạo dữ liệu mẫu cho trang Dành cho Chủ sân!');
    }
}
