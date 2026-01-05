<?php

namespace App\Models;

use App\Traits\Seoable;
use Illuminate\Database\Eloquent\Model;
use Str;


class Article extends Model
{
    use Seoable;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'thumbnail_url',
        'type',
        'author_id',
        'brand_id',
        'device_id',
        'published_at',
        'is_published',
        'meta_title',
        'meta_description',
        'views_count',
        'comments_count',
        'share_count',
        'allow_comments',
        'is_featured',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
        'views_count' => 'integer',
        'comments_count' => 'integer',
        'share_count' => 'integer',
    ];

    // FIXED ACCESSOR - Remove storage/ prefix since it's already in thumbnail_url
    public function getThumbnailUrlAttribute($value)
    {
        if ($value) {
            // Check if value already has storage/ prefix
            if (str_starts_with($value, 'storage/')) {
                return asset($value);
            }
            return asset('storage/' . $value);
        }
        return asset('images/default-article.jpg');
    }

    // In Article model
    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }

        // Generate excerpt from body if not set
        $excerpt = strip_tags($this->body);
        return Str::limit($excerpt, 200);
    }
    public function seo()
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tag');
    }

    public function comments()
    {
        return $this
            ->morphMany(Comment::class, 'commentable')
            ->where('is_approved', true);
    }

    // Scope for different types

    public function scopeNews($query)
    {
        return $query->where('type', 'news');
    }

    public function scopeBlog($query)
    {
        return $query->where('type', 'blog');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePublished($query)
    {
        return $query
            ->where('is_published', true)
            ->where('published_at', '<=', now());
    }
}
