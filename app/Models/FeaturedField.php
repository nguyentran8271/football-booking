<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedField extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'price',
        'hotline',
        'order'
    ];

    protected $casts = [
        'price' => 'float',
        'order' => 'integer'
    ];

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) return asset('images/default-field.jpg');
        if (str_starts_with($this->image, 'http')) return $this->image;
        if (str_starts_with($this->image, 'images/')) return asset($this->image);
        return asset('storage/' . $this->image);
    }
}
