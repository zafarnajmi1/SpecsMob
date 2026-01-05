<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeReviewSlider extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_link',
        'image',
    ];
}
