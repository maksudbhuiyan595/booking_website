<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_no',
        'passenger_name',
        'passenger_email',
        'passenger_phone',
        'trip_type',
        'pickup_address',
        'dropoff_address',
        'pickup_date',
        'pickup_time',
        'distance',
        'vehicle_type',
        'airline_name',
        'flight_number',
        'total_fare',
        'paid_amount',
        'due_amount',
        'payment_method',
        'payment_status',
        'transaction_id',
        'status',
    ];
}
