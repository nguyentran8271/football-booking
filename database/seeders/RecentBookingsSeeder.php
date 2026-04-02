<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Field;
use App\Models\User;
use App\Helpers\ShiftHelper;
use Carbon\Carbon;

class RecentBookingsSeeder extends Seeder
{
    public function run(): void
    {
        $fields = Field::all();
        $users  = User::where('role', 'user')->get();

        if ($fields->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Cần có fields và users trước!');
            return;
        }

        $dates = [
            '2026-02-24', '2026-02-25', '2026-02-26',
            '2026-02-27', '2026-02-28',
            '2026-03-01', '2026-03-03', '2026-03-05',
            '2026-03-10', '2026-03-15',
        ];

        $count = 0;

        foreach ($dates as $i => $date) {
            $field = $fields[$i % $fields->count()];
            $user  = $users[$i % $users->count()];
            $shift = ($i % 8) + 1;

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
                'total_price' => ShiftHelper::calculatePrice($field->price_per_hour),
                'status'      => 'approved',
                'created_at'  => Carbon::parse($date)->subDay(),
                'updated_at'  => Carbon::parse($date),
            ]);

            $count++;
        }

        $this->command->info("Đã tạo {$count} booking!");
    }
}
