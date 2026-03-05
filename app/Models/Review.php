<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'field_id',
        'rating',
        'field_quality_rating',
        'lighting_rating',
        'hygiene_rating',
        'staff_rating',
        'price_rating',
        'comment',
        'images',
        'helpful_count',
        'location',
    ];

    protected $casts = [
        'images' => 'array',
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
}
