<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceVariant extends Model
{
    protected $fillable = ['device_id', 'label', 'ram_gb', 'storage_gb', 'model_code', 'is_primary', 'status'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function offers()
    {
        return $this->hasMany(DeviceOffer::class);
    }
}
