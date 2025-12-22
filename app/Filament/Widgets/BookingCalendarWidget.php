<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BookingResource;
use App\Models\Booking;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid as ComponentsGrid;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\Filament\Actions\ViewAction;
use Guava\Calendar\Attributes\CalendarSchema;

class BookingCalendarWidget extends CalendarWidget
{
    protected bool $eventClickEnabled = true;
    protected bool $useFilamentTimezone = true;
    protected static ?int $sort = 2;


    public function getEvents(FetchInfo $fetchInfo): Collection | array
    {
        return Booking::query()
            ->where('pickup_date', '>=', $fetchInfo->start)
            ->where('pickup_date', '<=', $fetchInfo->end)
            ->get()
            ->map(function (Booking $booking) {

                $timeString = $booking->pickup_time;
                $startDateTime = Carbon::parse($booking->pickup_date->format('Y-m-d') . ' ' . $timeString);

                return CalendarEvent::make($booking)
                    ->title($booking->passenger_name ?? 'Unknown')
                    ->start($startDateTime->format('Y-m-d\TH:i:s'))
                    ->end($startDateTime->copy()->addHour()->format('Y-m-d\TH:i:s'))
                    ->backgroundColor($this->getStatusColor($booking->status))
                    ->action('viewBooking');
            })
            ->toArray();
    }

    public function viewBookingAction(): ViewAction
    {
        return ViewAction::make('viewBooking')
            ->model(Booking::class)
            ->modalHeading('Booking Details')
            ->modalFooterActionsAlignment('right');
    }

    #[CalendarSchema(Booking::class)]
    public function bookingSchema(Schema $schema): Schema
    {
        return $schema->components([
            ComponentsGrid::make(2)
                ->columnSpanFull()
                ->schema([
                    TextEntry::make('booking_no')->label('Booking #'),

                    TextEntry::make('status')
                        ->label('Status')
                        ->badge()
                        ->color(fn(string $state): string => match (strtolower($state)) {
                            'confirmed' => 'success',
                            'pending' => 'warning',
                            'cancelled' => 'danger',
                            default => 'gray',
                        }),

                    TextEntry::make('passenger_name')->label('Passenger'),
                    TextEntry::make('passenger_phone')->label('Phone'),

                    TextEntry::make('pickup_date_time')
                        ->label('Pickup Time')
                        ->getStateUsing(fn(Booking $record) => Carbon::parse($record->pickup_date->format('Y-m-d') . ' ' . $record->pickup_time)->format('M d, Y h:i A')),

                    TextEntry::make('trip_type')->label('Trip Type'),
                    TextEntry::make('pickup_address')->label('From/Pickup Address'),
                    TextEntry::make('dropoff_address')->label('To/Dropoff Address'),

                    TextEntry::make('vehicle_type')->label('Vehicle'),
                    TextEntry::make('total_fare')->prefix('$')->label('Total Fare'),
                     TextEntry::make('flight_number')->label('Flight Number'),
                ])
        ]);
    }

    protected function getStatusColor(?string $status): string
    {
        $status = strtolower($status ?? '');

        return match ($status) {
            'confirmed' => '#10b981', // Green
            'pending'   => '#f59e0b', // Yellow
            'cancelled' => '#ef4444', // Red
            'completed' => '#3b82f6', // Blue
            default     => '#6b7280', // Gray
        };
    }
}
