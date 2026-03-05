<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'image',
        'image_position',
        'order',
    ];
}
