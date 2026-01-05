<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'slug', 'type', 'is_popular'];

    public function posts()
    {
        return $this->belongsToMany(Article::class, 'post_tag');
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeArticleTags($query)
    {
        return $query->where('type', 'article');
    }
}

