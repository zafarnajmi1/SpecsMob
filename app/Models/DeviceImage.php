<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceImage extends Model
{
    protected $fillable = ['device_image_group_id', 'image_url', 'caption', 'order'];

    public function group()
    {
        return $this->belongsTo(DeviceImageGroup::class, 'device_image_group_id');
    }
}
