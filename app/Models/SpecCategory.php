<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecCategory extends Model
{
    protected $fillable = ['name', 'slug', 'order'];

    public function fields()
    {
        return $this->hasMany(SpecField::class)->orderBy('order');
    }
}
