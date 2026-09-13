<?php

namespace App\Models;

use Carbon\Carbon;
use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model implements Eventable
{
    protected $guarded = [];

    protected $casts = [
        'surcharge_details'    => 'array',
        'extra_charge_details' => 'array',
        'pickup_date'          => 'date',
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
