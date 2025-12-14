<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecField extends Model
{
    protected $fillable = ['spec_category_id', 'key', 'label', 'type', 'is_filterable', 'order'];

    public function category()
    {
        return $this->belongsTo(SpecCategory::class, 'spec_category_id'); // Specify if needed
    }

    public function values()
    {
        return $this->hasMany(DeviceSpecValue::class, 'spec_field_id'); // Specify the foreign key
    }
}
