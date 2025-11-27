<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $guarded = [];

    protected $casts = [
        'slabs' => 'array',
        'features' => 'array',
        'has_extra_seat' => 'boolean',
        'is_active' => 'boolean',
    ];
}
