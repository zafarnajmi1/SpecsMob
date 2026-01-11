<?php

namespace App\Models;

use App\Traits\HasTimeAgo;
use App\Traits\Seoable;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use Seoable;
    use HasTimeAgo;

    protected $fillable = [
        'device_id',
        'brand_id',
        'title',
        'slug',
        'cover_image_url',
        'body',
        'author_id',
        'published_at',
        'is_published',
        'views_count',
        'comments_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean'
    ];

    // ✅ Scopes for query filtering
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // In Review model
    public function getCoverImageUrlAttribute($value)
    {
        if ($value) {
            return asset('storage/' . $value);
        }
        return asset('user/images/default-preview.png');
    }

    public function seo()
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    // ✅ Get brand through device (remove direct brand relationship)
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function sections()
    {
        return $this->hasMany(DeviceReviewSection::class, 'device_review_id')->orderBy('order');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->where('is_approved', true)
            ->latest();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'review_tag');
    }

    // ✅ Helper method to check if published
    public function isPublished()
    {
        return $this->is_published &&
            $this->published_at &&
            $this->published_at->lte(now());
    }
}
