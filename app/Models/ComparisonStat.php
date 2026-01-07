<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComparisonStat extends Model
{
    protected $fillable = [
        'device1_id',
        'device2_id',
        'total_hits',
        'last_hit_at',
    ];

    public function device1()
    {
        return $this->belongsTo(Device::class, 'device1_id');
    }

    public function device2()
    {
        return $this->belongsTo(Device::class, 'device2_id');
    }

    /**
     * Increment the hit counter for a pair of devices.
     * Orders IDs to ensure the pair is unique.
     */
    public static function incrementHit($id1, $id2)
    {
        if (!$id1 || !$id2 || $id1 == $id2) {
            return;
        }

        $ids = [$id1, $id2];
        sort($ids);

        $stat = self::firstOrNew([
            'device1_id' => $ids[0],
            'device2_id' => $ids[1],
        ]);

        $stat->total_hits++;
        $stat->last_hit_at = now();
        $stat->save();
    }
}
