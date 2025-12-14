<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoDevice extends Model
{
    protected $fillable = [
        'video_id', 'device_id', 'order'
    ];
}
