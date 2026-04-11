<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'address',
        'price_per_hour',
        'description',
        'image',
        'status',
    ];

    /**
     * Quan hệ với Owner (User)
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Quan hệ với Bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Quan hệ với Reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Tính trung bình rating
     */
    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Lấy URL ảnh đúng cho cả public/images và storage
     */
    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('images/default-field.jpg');
        }
        if (str_starts_with($this->image, 'images/')) {
            return asset($this->image);
        }
        return asset('storage/' . $this->image);
    }
}
