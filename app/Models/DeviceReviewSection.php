<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DeviceReviewSection extends Model
{
    protected $fillable = [
        'device_review_id',
        'title',
        'slug',
        'order',
        'body'
    ];

    public function review()
    {
        return $this->belongsTo(Review::class, 'device_review_id');
    }
}
