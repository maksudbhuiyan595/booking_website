<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surcharge extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_percentage' => 'boolean',
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
