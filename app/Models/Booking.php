<?php

namespace App\Models;

use Carbon\Carbon;
use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model implements Eventable
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

    protected $casts = [
        'pickup_date' => 'date',
        'distance' => 'decimal:2',
        'total_fare' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    public function toCalendarEvent(): CalendarEvent
    {
        $startDateTime = Carbon::parse($this->pickup_date->format('Y-m-d') . ' ' . $this->pickup_time);

        return CalendarEvent::make($this)
            ->title("{$this->passenger_name} ({$this->pickup_time})")
            ->start($startDateTime)
            ->end($startDateTime->copy()->addHour())
            ->backgroundColor($this->status === 'confirmed' ? '#10b981' : '#f59e0b') // Example logic
            ->action('edit');
    }
}
