<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['name', 'slug', 'logo', 'description', 'status', 'cover_img'];
    
    protected $casts = [
        'status' => 'boolean'
    ];

    // ✅ Add scope for active brands
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    // ✅ Uncomment if you need news relationship
    // public function news()
    // {
    //     return $this->hasMany(DeviceNews::class);
    // }
}