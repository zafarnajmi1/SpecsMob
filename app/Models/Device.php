<?php

namespace App\Models;

use App\Traits\Seoable;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use Seoable;

    protected $fillable = [
        'brand_id',
        'device_type_id',
        'name',
        'slug',
        'description',
        'announcement_date',
        'release_status',
        'released_at',
        'color_short',
        'os_short',
        'chipset_short',
        'storage_short',
        'main_camera_short',
        'ram_short',
        'battery_short',
        'weight_grams',
        'dimensions',
        'thumbnail_url',
        'allow_opinions',
        'allow_fans',
        'is_published',
    ];

    protected $casts = [
        'announcement_date' => 'date',
        'released_at' => 'date',
        'allow_opinions' => 'boolean',
        'allow_fans' => 'boolean',
        'is_published' => 'boolean',
        'weight_grams' => 'decimal:2',
    ];

    public const STATUS_RUMORED = 'rumored';
    public const STATUS_ANNOUNCED = 'announced';
    public const STATUS_RELEASED = 'released';
    public const STATUS_DISCONTINUED = 'discontinued';

    public function getThumbnailUrlAttribute($value)
{
    if ($value) {
        return asset('storage/' . $value);
    }

    return asset('user/images/default-preview.png');
}


    public function seo()
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    public function stats()
    {
        return $this->hasOne(DeviceStat::class);
    }

    public function favorites()
    {
        return $this->hasMany(UserFavorite::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class);
    }

    public function variants()
    {
        return $this->hasMany(DeviceVariant::class);
    }

    public function imageGroups()
    {
        return $this->hasMany(DeviceImageGroup::class);
    }

    public function specs()
    {
        return $this->hasMany(DeviceSpecValue::class);
    }

    public function specCategories()
    {
        return $this
            ->belongsToMany(SpecCategory::class, 'device_spec_values', 'device_id', 'spec_field_id')
            ->withPivot(['value_string', 'value_number', 'value_json', 'unit'])
            ->withTimestamps();
    }

    // Or if you want to load via fields
    public function specValues()
    {
        return $this->hasMany(DeviceSpecValue::class);
    }

    // public function opinions()
    // {
    //     return $this->hasMany(DeviceOpinion::class);
    // }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

     public function videos()
    {
        return $this->belongsToMany(Video::class, 'video_devices')
                    ->withTimestamps()
                    ->withPivot('order')
                    ->orderBy('video_devices.order');
    }
}
