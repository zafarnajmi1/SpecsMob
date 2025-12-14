<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceImageGroup extends Model
{
     protected $fillable = ['device_id', 'title', 'slug', 'group_type', 'order'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function images()
    {
        return $this->hasMany(DeviceImage::class)->orderBy('order');
    }
}
