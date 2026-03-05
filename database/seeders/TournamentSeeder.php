<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tournament;
use App\Models\Field;
use Carbon\Carbon;

class TournamentSeeder extends Seeder
{
    public function run(): void
    {
        $ownerId = 2; // Owner test account
        $fields = Field::where('owner_id', $ownerId)->get();

        if ($fields->isEmpty()) {
            $this->command->warn('Không có sân nào cho owner_id = 2. Chạy OwnerFieldsSeeder trước.');
            return;
        }

        $tournaments = [
            [
                'owner_id' => $ownerId,
                'field_id' => $fields->first()->id,
                'name' => 'Giải bóng đá mùa hè 2026',
                'description' => 'Giải đấu bóng đá phong trào dành cho các đội nghiệp dư tại TP.HCM. Thi đấu theo thể thức vòng tròn một lượt, sau đó bán kết và chung kết.',
                'start_date' => Carbon::now()->addDays(30),
                'end_date' => Carbon::now()->addDays(45),
                'registration_deadline' => Carbon::now()->addDays(20),
                'max_teams' => 8,
                'players_per_team' => 7,
                'entry_fee' => 500000,
                'prize' => "Vô địch: 5.000.000đ + Cúp + Huy chương\nÁ quân: 3.000.000đ + Cúp + Huy chương\nHạng 3: 2.000.000đ + Huy chương\nVua phá lưới: 500.000đ",
                'status' => 'upcoming',
            ],
            [
                'owner_id' => $ownerId,
                'field_id' => $fields->count() > 1 ? $fields[1]->id : $fields->first()->id,
                'name' => 'Giải Futsal Cúp Mùa Xuân',
                'description' => 'Giải đấu Futsal 5 người chuyên nghiệp. Thể lệ thi đấu theo luật FIFA Futsal.',
                'start_date' => Carbon::now()->addDays(15),
                'end_date' => Carbon::now()->addDays(25),
                'registration_deadline' => Carbon::now()->addDays(10),
                'max_teams' => 12,
                'players_per_team' => 5,
                'entry_fee' => 300000,
                'prize' => "Vô địch: 3.000.000đ + Cúp\nÁ quân: 2.000.000đ + Cúp\nHạng 3: 1.000.000đ",
                'status' => 'upcoming',
            ],
            [
                'owner_id' => $ownerId,
                'field_id' => $fields->first()->id,
                'name' => 'Giải bóng đá Doanh nghiệp 2026',
                'description' => 'Giải đấu dành riêng cho các đội bóng của các doanh nghiệp, công ty tại khu vực. Tạo sân chơi lành mạnh, gắn kết nhân viên.',
                'start_date' => Carbon::now()->addDays(60),
                'end_date' => Carbon::now()->addDays(90),
                'registration_deadline' => Carbon::now()->addDays(50),
                'max_teams' => 16,
                'players_per_team' => 11,
                'entry_fee' => 1000000,
                'prize' => "Vô địch: 10.000.000đ + Cúp + Huy chương\nÁ quân: 6.000.000đ + Cúp + Huy chương\nHạng 3: 3.000.000đ + Huy chương\nHạng 4: 1.000.000đ",
                'status' => 'upcoming',
            ],
        ];

        foreach ($tournaments as $tournament) {
            Tournament::create($tournament);
        }

        $this->command->info('Đã tạo ' . count($tournaments) . ' giải đấu mẫu.');
    }
}
