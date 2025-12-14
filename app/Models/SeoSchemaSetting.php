<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoSchemaSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_schema',
        'article_schema_template',
        'product_schema_template',
    ];
}
