<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BookingCalendarWidget;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class BookingCalendar extends Page
{
    use HasPageShield;
    // protected string $view = 'filament.pages.booking-calendar';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Calendar;
    protected static \UnitEnum|string|null $navigationGroup = 'Bookings Management';
    protected static ?int $navigationSort = 2;

    protected function getHeaderWidgets(): array
    {
        return [
            BookingCalendarWidget::class,
        ];
    }
}
