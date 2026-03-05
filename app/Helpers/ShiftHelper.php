<?php

namespace App\Helpers;

class ShiftHelper
{
    /**
     * Danh sách các ca trong ngày
     */
    public static function getShifts()
    {
        return [
            1 => ['start' => '07:00', 'end' => '09:00', 'label' => 'Ca 1 (07:00 - 09:00)'],
            2 => ['start' => '09:00', 'end' => '11:00', 'label' => 'Ca 2 (09:00 - 11:00)'],
            3 => ['start' => '11:00', 'end' => '13:00', 'label' => 'Ca 3 (11:00 - 13:00)'],
            4 => ['start' => '13:00', 'end' => '15:00', 'label' => 'Ca 4 (13:00 - 15:00)'],
            5 => ['start' => '15:00', 'end' => '17:00', 'label' => 'Ca 5 (15:00 - 17:00)'],
            6 => ['start' => '17:00', 'end' => '19:00', 'label' => 'Ca 6 (17:00 - 19:00)'],
            7 => ['start' => '19:00', 'end' => '21:00', 'label' => 'Ca 7 (19:00 - 21:00)'],
            8 => ['start' => '21:00', 'end' => '23:00', 'label' => 'Ca 8 (21:00 - 23:00)'],
        ];
    }

    /**
     * Lấy thông tin ca
     */
    public static function getShiftInfo($shift)
    {
        $shifts = self::getShifts();
        return $shifts[$shift] ?? null;
    }

    /**
     * Lấy label của ca
     */
    public static function getShiftLabel($shift)
    {
        $info = self::getShiftInfo($shift);
        return $info ? $info['label'] : 'Ca không xác định';
    }

    /**
     * Lấy giờ bắt đầu của ca
     */
    public static function getShiftStartTime($shift)
    {
        $info = self::getShiftInfo($shift);
        return $info ? $info['start'] : null;
    }

    /**
     * Lấy giờ kết thúc của ca
     */
    public static function getShiftEndTime($shift)
    {
        $info = self::getShiftInfo($shift);
        return $info ? $info['end'] : null;
    }

    /**
     * Tính giá cho 1 ca (2 tiếng)
     */
    public static function calculatePrice($pricePerHour)
    {
        return $pricePerHour * 2; // Mỗi ca 2 tiếng
    }
}
