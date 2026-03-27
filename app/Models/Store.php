<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Store extends Model
{
    protected $fillable = ['name', 'slug', 'logo_url'];

    /**
     * Get the device offers for this store.
     */
    public function deviceOffers(): HasMany
    {
        return $this->hasMany(DeviceOffer::class);
    }

    /**
     * Get the logo URL attribute.
     */
    public function getLogoUrlAttribute($value)
    {
        if ($value) {
            if (str_starts_with($value, 'http')) {
                return $value;
            }
            return \Storage::disk('s3')->url($value);
        }
        return null;
    }

    public function getLogoPathAttribute()
    {
        return $this->attributes['logo_url'] ?? null;
    }
}
