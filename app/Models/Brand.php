<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Brand extends Model
{
    use \App\Traits\Seoable;
    protected $fillable = ['name', 'slug', 'logo', 'description', 'status', 'cover_img'];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            if (str_starts_with($this->logo, 'http')) {
                return $this->logo;
            }
            return \Storage::disk('s3')->url($this->logo);
        }
        return asset('images/default-brand.png'); // Placeholder
    }

    public function getCoverUrlAttribute()
    {
        if ($this->cover_img) {
            if (str_starts_with($this->cover_img, 'http')) {
                return $this->cover_img;
            }
            return \Storage::disk('s3')->url($this->cover_img);
        }
        return null;
    }

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