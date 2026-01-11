<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'type',
        'name',
        'email',
        'subject',
        'message',
        'ip_address',
        'is_read'
    ];
}
