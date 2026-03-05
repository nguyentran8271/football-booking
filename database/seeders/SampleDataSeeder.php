<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Field;
use App\Models\Post;
use App\Models\Review;
use App\Models\SiteSetting;
use App\Models\User;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy owner
        $owner = User::where('role', 'owner')->first();
        $user = User::where('role', 'user')->first();

        // Tạo sân mẫu
        $fields = [
            [
                'owner_id' => $owner->id,
                'name' => 'Sân bóng Thành Công',
                'address' => '123 Đường ABC, Quận 1, TP.HCM',
                'price_per_hour' => 200000,
                'description' => 'Sân bóng cỏ nhân tạo chất lượng cao, đầy đủ tiện nghi',
                'status' => 'active',
            ],
            [
                'owner_id' => $owner->id,
                'name' => 'Sân bóng Hòa Bình',
                'address' => '456 Đường XYZ, Quận 2, TP.HCM',
                'price_per_hour' => 150000,
                'description' => 'Sân bóng mini 5 người, có mái che',
                'status' => 'active',
            ],
            [
                'owner_id' => $owner->id,
                'name' => 'Sân bóng Đại Thắng',
                'address' => '789 Đường DEF, Quận 3, TP.HCM',
                'price_per_hour' => 180000,
                'description' => 'Sân bóng 7 người, ánh sáng tốt',
                'status' => 'active',
            ],
            [
                'owner_id' => $owner->id,
                'name' => 'Sân bóng Vạn Phúc',
                'address' => '321 Đường GHI, Quận 4, TP.HCM',
                'price_per_hour' => 220000,
                'description' => 'Sân bóng 11 người, cỏ tự nhiên',
                'status' => 'active',
            ],
        ];

        foreach ($fields as $fieldData) {
            $field = Field::create($fieldData);

            // Tạo reviews cho mỗi sân
            Review::create([
                'user_id' => $user->id,
                'field_id' => $field->id,
                'rating' => 5,
                'comment' => 'Sân rất đẹp, cỏ mượt, giá hợp lý!',
            ]);

            Review::create([
                'user_id' => $user->id,
                'field_id' => $field->id,
                'rating' => 4,
                'comment' => 'Sân tốt, phục vụ nhiệt tình!',
            ]);
        }

        // Tạo bài viết mẫu
        $posts = [
            [
                'title' => 'Việt Nam vô địch AFF Cup 2024',
                'category' => 'trong_nuoc',
                'content' => 'Đội tuyển Việt Nam đã xuất sắc vô địch AFF Cup 2024 sau trận chung kết kịch tính trước Thái Lan với tỷ số 2-1. Đây là lần thứ 3 Việt Nam vô địch giải đấu này.',
            ],
            [
                'title' => 'Messi giành Quả bóng vàng thứ 8',
                'category' => 'ngoai_nuoc',
                'content' => 'Lionel Messi tiếp tục khẳng định đẳng cấp với danh hiệu Quả bóng vàng thứ 8 trong sự nghiệp, sau mùa giải thành công cùng Inter Miami.',
            ],
            [
                'title' => 'V-League 2024: Hà Nội FC dẫn đầu',
                'category' => 'trong_nuoc',
                'content' => 'Hà Nội FC đang dẫn đầu bảng xếp hạng V-League 2024 với 45 điểm sau 20 vòng đấu, bỏ xa đội nhì bảng 5 điểm.',
            ],
            [
                'title' => 'Champions League: Real Madrid vào chung kết',
                'category' => 'ngoai_nuoc',
                'content' => 'Real Madrid đã vượt qua Manchester City để giành vé vào chung kết Champions League sau trận cầu nghẹt thở với tỷ số chung cuộc 4-3.',
            ],
            [
                'title' => 'U23 Việt Nam chuẩn bị cho SEA Games',
                'category' => 'trong_nuoc',
                'content' => 'Đội tuyển U23 Việt Nam đang tích cực tập luyện để chuẩn bị cho SEA Games 32 sắp tới, với mục tiêu bảo vệ tấm HCV.',
            ],
            [
                'title' => 'Premier League: Arsenal đua vô địch',
                'category' => 'ngoai_nuoc',
                'content' => 'Arsenal đang có phong độ ấn tượng và đang cạnh tranh ngôi vô địch Premier League với Manchester City trong cuộc đua nghẹt thở.',
            ],
        ];

        foreach ($posts as $postData) {
            Post::create($postData);
        }

        // Tạo site settings
        $settings = [
            'site_name' => 'Đặt Sân Bóng',
            'site_slogan' => 'Đặt sân nhanh - Chơi bóng vui',
            'site_description' => 'Hệ thống đặt sân bóng trực tuyến hàng đầu Việt Nam',
            'site_phone' => '0123456789',
            'site_email' => 'info@datsanbong.vn',
            'site_hotline' => '1900xxxx',
            'site_address' => 'Hà Nội, Việt Nam',
            'hero_title' => 'Đặt Sân Bóng Dễ Dàng',
            'hero_description' => 'Tìm và đặt sân bóng chất lượng gần bạn',
            'about_title' => 'Về Chúng Tôi',
            'about_description' => 'Chúng tôi cung cấp dịch vụ đặt sân bóng trực tuyến tiện lợi, nhanh chóng và đáng tin cậy.',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::create([
                'key' => $key,
                'value' => $value,
            ]);
        }

        // Tạo Home Cards mẫu
        \App\Models\HomeCard::create(['title' => 'Dễ dàng đặt sân', 'description' => 'Chỉ với vài thao tác đơn giản, bạn có thể đặt sân bóng yêu thích', 'order' => 1]);
        \App\Models\HomeCard::create(['title' => 'Giá cả hợp lý', 'description' => 'Nhiều mức giá phù hợp với mọi túi tiền', 'order' => 2]);
        \App\Models\HomeCard::create(['title' => 'Sân chất lượng', 'description' => 'Tất cả sân đều được kiểm duyệt kỹ lưỡng', 'order' => 3]);

        // Tạo Home Stats mẫu
        \App\Models\HomeStat::create(['title' => 'Người dùng', 'value' => '1000+', 'order' => 1]);
        \App\Models\HomeStat::create(['title' => 'Sân bóng', 'value' => '50+', 'order' => 2]);
        \App\Models\HomeStat::create(['title' => 'Lượt đặt', 'value' => '5000+', 'order' => 3]);
        \App\Models\HomeStat::create(['title' => 'Chủ sân', 'value' => '30+', 'order' => 4]);

        // Tạo Featured Fields mẫu
        \App\Models\FeaturedField::create([
            'title' => 'Sân bóng Thành Công',
            'description' => '123 Đường ABC, Quận 1, TP.HCM',
            'price' => '200000',
            'order' => 1
        ]);
        \App\Models\FeaturedField::create([
            'title' => 'Sân bóng Hòa Bình',
            'description' => '456 Đường XYZ, Quận 2, TP.HCM',
            'price' => '150000',
            'order' => 2
        ]);
        \App\Models\FeaturedField::create([
            'title' => 'Sân bóng Đại Thắng',
            'description' => '789 Đường DEF, Quận 3, TP.HCM',
            'price' => '180000',
            'order' => 3
        ]);
        \App\Models\FeaturedField::create([
            'title' => 'Sân bóng Vạn Phúc',
            'description' => '321 Đường GHI, Quận 4, TP.HCM',
            'price' => '220000',
            'order' => 4
        ]);

        // Tạo About Sections mẫu
        \App\Models\AboutSection::create([
            'title' => 'Về Chúng Tôi',
            'content' => 'Chúng tôi là nền tảng đặt sân bóng trực tuyến hàng đầu tại Việt Nam, mang đến trải nghiệm đặt sân nhanh chóng, tiện lợi và đáng tin cậy. Với hệ thống sân bóng chất lượng cao trên toàn quốc, chúng tôi cam kết mang đến dịch vụ tốt nhất cho khách hàng.',
            'layout' => 'image-left',
            'order' => 1
        ]);
        \App\Models\AboutSection::create([
            'title' => 'Sứ Mệnh',
            'content' => 'Kết nối người chơi bóng đá với các sân bóng chất lượng, tạo điều kiện thuận lợi nhất cho việc tổ chức các trận đấu. Chúng tôi luôn nỗ lực để mang đến trải nghiệm đặt sân tốt nhất, giúp mọi người dễ dàng tìm kiếm và đặt sân phù hợp với nhu cầu.',
            'layout' => 'image-right',
            'order' => 2
        ]);
        \App\Models\AboutSection::create([
            'title' => 'Tầm Nhìn',
            'content' => 'Trở thành nền tảng đặt sân bóng số 1 Việt Nam, phục vụ hàng triệu người chơi bóng đá mỗi năm. Chúng tôi hướng tới việc xây dựng một cộng đồng bóng đá mạnh mẽ, nơi mọi người có thể dễ dàng kết nối và tận hưởng niềm đam mê với trái bóng tròn.',
            'layout' => 'image-left',
            'order' => 3
        ]);
    }
}
