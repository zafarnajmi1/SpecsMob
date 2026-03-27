<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HomeReviewSlider extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_link',
        'image',
    ];

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            if (str_starts_with($this->image, 'http')) {
                return $this->image;
            }
            return \Storage::disk('s3')->url($this->image);
        }
        return null;
    }
}
