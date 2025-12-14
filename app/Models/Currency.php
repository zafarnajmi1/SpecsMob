<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    protected $fillable = ['name', 'iso_code', 'symbol'];

    /**
     * Get the device offers for this currency.
     */
    public function deviceOffers(): HasMany
    {
        return $this->hasMany(DeviceOffer::class);
    }
}