<?php

namespace App\Traits;

use App\Models\SeoMeta;
use Illuminate\Http\Request;

trait HasStatus
{
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}

// Use in Brand, Device, etc.