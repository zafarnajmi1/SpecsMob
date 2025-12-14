<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'default_meta_title',
        'default_meta_description',
        'default_meta_keywords',
        'robots_default',
        'canonical_base_url',
        'og_site_name',
        'og_default_title',
        'og_default_description',
        'og_default_image',
        'twitter_default_title',
        'twitter_default_description',
        'twitter_default_image',
    ];
}
