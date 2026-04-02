<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Field;
use App\Models\User;
use App\Helpers\ShiftHelper;
use Carbon\Carbon;

class BookingRevenueSeeder extends Seeder
{
    public function run(): void
    {
        $fields = Field::all();
        $users  = User::where('role', 'user')->get();

        if ($fields->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Cần có fields và users trước!');
            return;
        }

        // 12 booking trong năm 2025 (rải đều các tháng)
        $dates2025 = [
            '2025-01-15', '2025-02-20', '2025-03-10',
            '2025-04-05', '2025-05-18', '2025-06-22',
            '2025-07-08', '2025-08-14', '2025-09-25',
            '2025-10-03', '2025-11-17', '2025-12-28',
        ];

        // 8 booking từ đầu 2026 đến nay (tháng 1, 2, 3)
        $dates2026 = [
            '2026-01-05', '2026-01-20',
            '2026-02-08', '2026-02-22',
            '2026-03-01', '2026-03-07', '2026-03-14', '2026-03-19',
        ];

        $allDates = array_merge($dates2025, $dates2026);
        $count = 0;

        foreach ($allDates as $i => $date) {
            $field = $fields[$i % $fields->count()];
            $user  = $users[$i % $users->count()];
            $shift = ($i % 8) + 1;
            $totalPrice = ShiftHelper::calculatePrice($field->price_per_hour);

            // Tránh trùng ca
            $exists = Booking::where('field_id', $field->id)
                ->where('date', $date)
                ->where('shift', $shift)
                ->exists();

            if ($exists) {
                $shift = ($shift % 8) + 1;
            }

            Booking::create([
                'user_id'     => $user->id,
                'field_id'    => $field->id,
                'date'        => $date,
                'shift'       => $shift,
                'total_price' => $totalPrice,
                'status'      => 'approved',
                'created_at'  => Carbon::parse($date)->subDays(rand(1, 3)),
                'updated_at'  => Carbon::parse($date)->subDays(rand(0, 1)),
            ]);

            $count++;
        }

        $this->command->info("Đã tạo {$count} booking approved!");
    }
}
