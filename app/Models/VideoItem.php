<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoItem extends Model
{
    protected $fillable = [
        'video_id', 'title', 'youtube_id', 'description', 
        'brand_id', 'device_id', 'is_published', 'order'
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}