<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Field;
use App\Models\Booking;

class FieldReviewsSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $fields = Field::all();

        if ($users->isEmpty() || $fields->isEmpty()) {
            $this->command->warn('Cần có users và fields trước!');
            return;
        }

        $reviews = [
            [5, 'Sân cỏ xanh mướt, mặt sân phẳng, không bị lồi lõm. Ánh sáng ban đêm rất tốt, đá thoải mái. Vệ sinh sạch sẽ, nhân viên thân thiện. Sẽ quay lại!'],
            [5, 'Chất lượng sân tuyệt vời, cỏ nhân tạo mới và êm. Hệ thống đèn chiếu sáng đầy đủ. Phòng着 着 thay đồ sạch sẽ. Giá cả hợp lý so với chất lượng.'],
            [4, 'Sân rộng rãi, thoáng mát. Cỏ còn tốt, ít bị mòn. Ánh sáng ổn nhưng góc cuối sân hơi tối. Nhân viên nhiệt tình. Nhìn chung rất ổn!'],
            [5, 'Đây là sân tốt nhất khu vực! Mặt sân phẳng, cỏ đều. Đèn chiếu sáng cực tốt, đá đêm như ban ngày. Vệ sinh sạch, có chỗ để xe rộng.'],
            [4, 'Sân chất lượng, cỏ mềm không bị cứng. Ánh sáng đủ để đá tối. Giá hơi cao nhưng xứng đáng với chất lượng. Sẽ đặt lại lần sau.'],
            [3, 'Sân tạm ổn, cỏ có vài chỗ bị mòn. Ánh sáng đủ dùng. Nhà vệ sinh cần cải thiện thêm. Nhân viên ok. Giá hợp lý.'],
            [5, 'Sân mới, cỏ xanh đẹp. Hệ thống tưới cỏ tốt nên mặt sân luôn ẩm và êm. Đèn sáng rõ. Có ghế ngồi cho người xem. Rất chuyên nghiệp!'],
            [4, 'Chất lượng sân tốt, phù hợp để đá phong trào lẫn thi đấu. Cỏ đều, không bị gồ ghề. Ánh sáng tốt. Nhân viên hỗ trợ nhiệt tình.'],
            [5, 'Sân đẹp, sạch sẽ. Cỏ nhân tạo chất lượng cao, không bị trơn khi trời mưa. Đèn chiếu sáng đồng đều. Giá cả phải chăng. Highly recommend!'],
            [4, 'Sân rộng, thoáng. Cỏ còn mới, chưa bị mòn nhiều. Ánh sáng ổn. Có chỗ để xe máy và ô tô. Nhân viên thân thiện, hỗ trợ tốt.'],
            [3, 'Sân bình thường, cỏ hơi cũ ở khu vực giữa sân. Ánh sáng đủ dùng nhưng không đều. Giá hợp lý. Nhân viên ok. Cần nâng cấp thêm.'],
            [5, 'Tuyệt vời! Sân mới nâng cấp, cỏ xanh mướt. Hệ thống đèn LED sáng rõ. Phòng thay đồ sạch sẽ có tủ khóa. Giá cả hợp lý. 5 sao!'],
            [4, 'Sân chất lượng tốt, phù hợp đá 5 người. Cỏ mềm, không bị cứng. Ánh sáng đủ. Có bán nước và đồ ăn nhẹ. Sẽ quay lại thường xuyên.'],
            [5, 'Sân đẹp nhất mình từng đặt! Cỏ xanh, phẳng, không bị lồi. Đèn sáng như ban ngày. Vệ sinh cực sạch. Nhân viên chuyên nghiệp. 10/10!'],
            [4, 'Sân ổn, cỏ tốt. Ánh sáng đủ để đá tối. Có chỗ ngồi nghỉ giữa hiệp. Giá hơi cao vào cuối tuần nhưng chất lượng xứng đáng.'],
            [5, 'Rất hài lòng! Sân mới, cỏ nhân tạo chất lượng cao. Hệ thống đèn tốt. Khu vực vệ sinh sạch sẽ. Nhân viên nhiệt tình. Giá hợp lý.'],
            [3, 'Sân tạm được, cỏ có chỗ bị hỏng cần vá. Ánh sáng ổn. Nhà vệ sinh cần dọn thường xuyên hơn. Nhân viên thân thiện. Giá ok.'],
            [4, 'Sân rộng, thoáng mát. Cỏ đều, không bị gồ ghề. Ánh sáng tốt. Có bãi đỗ xe rộng. Nhân viên hỗ trợ tốt. Sẽ giới thiệu cho bạn bè.'],
            [5, 'Sân chất lượng cao, cỏ xanh mướt. Đèn chiếu sáng đồng đều toàn sân. Vệ sinh sạch sẽ. Có phòng thay đồ riêng. Giá cả hợp lý. Tuyệt!'],
            [4, 'Đặt sân lần đầu ở đây, rất hài lòng. Sân đẹp, cỏ tốt. Ánh sáng đủ. Nhân viên thân thiện, hướng dẫn tận tình. Sẽ quay lại!'],
        ];

        $count = 0;
        foreach ($reviews as $i => [$rating, $comment]) {
            $user = $users->random();
            $field = $fields->random();

            Review::create([
                'user_id'              => $user->id,
                'field_id'             => $field->id,
                'rating'               => $rating,
                'field_quality_rating' => max(1, min(5, $rating + (rand(-3, 3) / 10))),
                'lighting_rating'      => max(1, min(5, $rating + (rand(-3, 3) / 10))),
                'hygiene_rating'       => max(1, min(5, $rating + (rand(-3, 3) / 10))),
                'staff_rating'         => max(1, min(5, $rating + (rand(-3, 3) / 10))),
                'price_rating'         => max(1, min(5, $rating + (rand(-3, 3) / 10))),
                'comment'              => $comment,
                'helpful_count'        => rand(0, 30),
                'created_at'           => now()->subDays(rand(1, 120)),
                'updated_at'           => now()->subDays(rand(1, 120)),
            ]);
            $count++;
        }

        $this->command->info("Đã tạo {$count} đánh giá sân!");
    }
}
