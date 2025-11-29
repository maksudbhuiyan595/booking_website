<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtraCharge extends Model
{
    protected $guarded = [];

    protected $casts = [
        'zip_codes' => 'array',
        'price' => 'decimal:2',
        'toll_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
