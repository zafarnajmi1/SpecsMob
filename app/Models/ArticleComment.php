<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleComment extends Model
{
    protected $fillable = [
        'article_id',
        'user_id',
        'parent_id',
        'body',
        'is_approved',
        'likes_count',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(ArticleComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(ArticleComment::class, 'parent_id');
    }
}
