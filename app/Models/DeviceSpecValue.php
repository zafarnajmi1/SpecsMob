<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceSpecValue extends Model
{
    protected $fillable = [
        'device_id',
        'spec_field_id',
        'value_string',
        'value_number',
        'value_json',
        'unit',
    ];

    protected $casts = [
        'value_json' => 'array',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function field()
    {
        return $this->belongsTo(SpecField::class, 'spec_field_id'); // Specify the foreign key
    }

}
