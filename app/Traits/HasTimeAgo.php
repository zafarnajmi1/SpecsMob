<?php

namespace App\Traits;

use App\Models\SeoMeta;
use Illuminate\Http\Request;

trait HasTimeAgo
{
    public function getTimeAgoAttribute()
    {
        return $this->published_at->diffForHumans();
    }
}