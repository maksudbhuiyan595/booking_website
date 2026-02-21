<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use App\Mail\AdminBookingConfirmationMail;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $booking = $this->record;
        if ($booking->passenger_email) {
            Mail::to($booking->passenger_email)->send(new AdminBookingConfirmationMail($booking));
        }
    }
}
