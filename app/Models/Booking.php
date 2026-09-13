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

    protected static function booted()
    {
        static::creating(function ($booking) {
            if (empty($booking->booking_no) || str_starts_with($booking->booking_no, 'BLAT-')) {
                // Fetch latest booking that matches the BLAT format
                $lastBooking = self::orderBy('id', 'desc')->first();
                $lastNumber = 0;
                
                if ($lastBooking && preg_match('/BLAT-(\d+)/', $lastBooking->booking_no, $matches)) {
                    $lastNumber = (int) $matches[1];
                }
                
                // Set explicitly with str_pad for zero-padding (fixes 0023 vs 23)
                $booking->booking_no = 'BLAT-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function toCalendarEvent(): CalendarEvent
    {
        $startDateTime = Carbon::parse(
            $this->pickup_date->format('Y-m-d') . ' ' . $this->pickup_time
        );

        return CalendarEvent::make($this)
            ->title("{$this->passenger_name} ({$this->pickup_time})")
            ->start($startDateTime)
            ->end($startDateTime->copy()->addHour())
            ->backgroundColor(
                $this->status === 'confirmed'
                    ? '#10b981'
                    : '#f59e0b'
            )
            ->action('edit');
    }
}

// <?php

// namespace App\Models;

// use Carbon\Carbon;
// use Guava\Calendar\Contracts\Eventable;
// use Guava\Calendar\ValueObjects\CalendarEvent;
// use Illuminate\Database\Eloquent\Model;

// class Booking extends Model implements Eventable
// {
//     protected $guarded = [];

//     protected $casts = [
//         'surcharge_details'    => 'array',
//         'extra_charge_details' => 'array',
//         'pickup_date'          => 'date',
//     ];

//     public function toCalendarEvent(): CalendarEvent
//     {
//         $startDateTime = Carbon::parse(
//             $this->pickup_date->format('Y-m-d') . ' ' . $this->pickup_time
//         );

//         return CalendarEvent::make($this)
//             ->title("{$this->passenger_name} ({$this->pickup_time})")
//             ->start($startDateTime)
//             ->end($startDateTime->copy()->addHour())
//             ->backgroundColor(
//                 $this->status === 'confirmed'
//                     ? '#10b981'
//                     : '#f59e0b'
//             )
//             ->action('edit');
//     }
// }
