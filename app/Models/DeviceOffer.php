<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceOffer extends Model
{
    protected $fillable = [
        'device_variant_id', 'country_id', 'currency_id', 'store_id',
        'url', 'price', 'in_stock', 'is_featured', 'status'
    ];

    public function variant()
    {
        return $this->belongsTo(DeviceVariant::class, 'device_variant_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function priceHistory()
    {
        return $this->hasMany(DeviceOfferPriceHistory::class);
    }
}
