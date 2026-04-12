<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Booking;
use Carbon\Carbon;

class SampleOwnerBookingsSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('email', 'owner@gmail.com')->first();
        if (!$owner) {
            $this->command->warn('Không tìm thấy owner@gmail.com');
            return;
        }

        $fields = $owner->fields;
        if ($fields->isEmpty()) {
            $this->command->warn('Chủ sân mẫu chưa có sân');
            return;
        }

        $users = User::where('role', 'user')->take(20)->get();
        if ($users->isEmpty()) {
            $this->command->warn('Không có user nào');
            return;
        }

        $shifts = [1, 2, 3, 4, 5, 6];
        $count = 0;
        $usedSlots = [];

        foreach ($fields as $field) {
            // 2025: mỗi tháng 4-6 booking
            for ($month = 1; $month <= 12; $month++) {
                for ($b = 0; $b < rand(4, 6); $b++) {
                    $day = rand(1, 28);
                    $date = Carbon::create(2025, $month, $day)->format('Y-m-d');
                    $shift = $shifts[array_rand($shifts)];
                    $key = $field->id . '_' . $date . '_' . $shift;
                    if (in_array($key, $usedSlots)) continue;
                    $usedSlots[] = $key;

                    Booking::create([
                        'user_id'     => $users->random()->id,
                        'field_id'    => $field->id,
                        'date'        => $date,
                        'shift'       => $shift,
                        'total_price' => $field->price_per_hour,
                        'status'      => 'approved',
                        'created_at'  => Carbon::create(2025, $month, $day)->subDays(2),
                        'updated_at'  => Carbon::create(2025, $month, $day)->subDays(1),
                    ]);
                    $count++;
                }
            }

            // 2026: tháng 1-4
            for ($month = 1; $month <= 4; $month++) {
                for ($b = 0; $b < rand(5, 8); $b++) {
                    $day = rand(1, 28);
                    $date = Carbon::create(2026, $month, $day)->format('Y-m-d');
                    if (Carbon::parse($date)->isFuture()) continue;
                    $shift = $shifts[array_rand($shifts)];
                    $key = $field->id . '_' . $date . '_' . $shift;
                    if (in_array($key, $usedSlots)) continue;
                    $usedSlots[] = $key;

                    Booking::create([
                        'user_id'     => $users->random()->id,
                        'field_id'    => $field->id,
                        'date'        => $date,
                        'shift'       => $shift,
                        'total_price' => $field->price_per_hour,
                        'status'      => 'approved',
                        'created_at'  => Carbon::create(2026, $month, $day)->subDays(2),
                        'updated_at'  => Carbon::create(2026, $month, $day)->subDays(1),
                    ]);
                    $count++;
                }
            }
        }

        $this->command->info("Đã tạo {$count} bookings cho chủ sân mẫu.");
    }
}
