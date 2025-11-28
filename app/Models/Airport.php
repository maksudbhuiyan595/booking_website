<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
     protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'pickup_fee' => 'decimal:2',
        'dropoff_fee' => 'decimal:2',
    ];
}
