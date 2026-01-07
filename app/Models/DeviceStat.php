<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceStat extends Model
{
    protected $fillable = [
        'device_id',
        'total_hits',
        'total_fans',
        'popularity_score',
        'daily_hits',
        'daily_hits_date',
        'last_hit_at',
        'last_fan_at',
    ];

    protected $casts = [
        'popularity_score' => 'decimal:2',
        'last_hit_at' => 'datetime',
        'last_fan_at' => 'datetime',
        'daily_hits_date' => 'datetime',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Increment the hit counter for this device
     * Automatically handles daily reset logic
     */
    public function incrementHit()
    {
        $today = now()->startOfDay();

        // Reset daily hits if it's a new day
        if (!$this->daily_hits_date || !$this->daily_hits_date->isSameDay($today)) {
            $this->daily_hits = 0;
            $this->daily_hits_date = $today;
        }

        $this->increment('daily_hits');
        $this->increment('total_hits');
        $this->last_hit_at = now();
        $this->save();
    }

    /**
     * Increment the fan counter for this device
     */
    public function incrementFan()
    {
        $this->increment('total_fans');
        $this->last_fan_at = now();
        $this->save();
    }
}
