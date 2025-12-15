<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceOpinion extends Model
{
    protected $fillable = [
        'device_id', 'user_id', 'parent_id',
        'title', 'body', 'rating', 'is_approved', 'likes_count',
    ];

    // Polymorphic relation to comments
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(DeviceOpinion::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(DeviceOpinion::class, 'parent_id');
    }
}
