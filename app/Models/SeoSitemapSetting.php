<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoSitemapSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'sitemap_url',
        'robots_content',
        'hreflang_en',
        'hreflang_en_pk',
        'hreflang_en_in',
    ];
}
