<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ShiftHelper;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'field_id',
        'date',
        'shift',
        'total_price',
        'status',
        'is_read',
        'user_notified',
        'cancel_reason',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Quan hệ với User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ với Field
     */
    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    /**
     * Lấy thông tin ca
     */
    public function getShiftInfoAttribute()
    {
        return ShiftHelper::getShiftInfo($this->shift);
    }

    /**
     * Lấy label ca
     */
    public function getShiftLabelAttribute()
    {
        return ShiftHelper::getShiftLabel($this->shift);
    }

    /**
     * Lấy giờ bắt đầu
     */
    public function getStartTimeAttribute()
    {
        return ShiftHelper::getShiftStartTime($this->shift);
    }

    /**
     * Lấy giờ kết thúc
     */
    public function getEndTimeAttribute()
    {
        return ShiftHelper::getShiftEndTime($this->shift);
    }

    /**
     * Kiểm tra ca đã được đặt chưa
     */
    public static function isShiftBooked($fieldId, $date, $shift, $excludeId = null)
    {
        $query = self::where('field_id', $fieldId)
            ->where('date', $date)
            ->where('shift', $shift)
            ->where('status', '!=', 'cancelled');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Lấy các ca đã được đặt trong ngày
     */
    public static function getBookedShifts($fieldId, $date)
    {
        return self::where('field_id', $fieldId)
            ->where('date', $date)
            ->where('status', '!=', 'cancelled')
            ->pluck('shift')
            ->toArray();
    }
}
