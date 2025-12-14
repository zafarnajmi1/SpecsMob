<?php

namespace App\Models;

use App\Traits\Seoable;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use Seoable;

    protected $fillable = ['title', 'slug', 'youtube_id', 'thumbnail_url', 'description', 'author_id', 'published_at', 'is_published', 'views_count', 'comments_count'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at');
    }

    public function seo()
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

        public function devices()
    {
        // pivot: video_devices
        return $this->belongsToMany(Device::class, 'video_devices')
                    ->withTimestamps()
                    ->withPivot('order')
                    ->orderBy('video_devices.order');
    }

    public function brands()
    {
        // pivot: video_brands
        return $this->belongsToMany(Brand::class, 'video_brands')
                    ->withTimestamps();
    }

     public function videoItems()
    {
        return $this->hasMany(VideoItem::class);
    }
}
