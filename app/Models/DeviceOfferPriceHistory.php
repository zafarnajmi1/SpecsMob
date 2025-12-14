<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceOfferPriceHistory extends Model
{
    protected $fillable = [
        'device_offer_id',
        'price',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'price'       => 'decimal:2',
    ];
}
