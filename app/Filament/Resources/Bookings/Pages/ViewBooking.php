<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use App\Models\Booking;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([

                // 1. Customer Details
                Section::make('Customer Information')
                    ->icon('heroicon-m-user')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('passenger_name')->label('Name')->placeholder('-'),
                            TextEntry::make('passenger_email')->label('Email')->icon('heroicon-m-envelope')->copyable()->placeholder('-'),
                            TextEntry::make('passenger_phone')->label('Phone')->icon('heroicon-m-phone')->url(fn($record) => "tel:{$record->passenger_phone}")->placeholder('-'),
                            TextEntry::make('alternate_phone')->label('Alt. Phone')->placeholder('-'),
                            TextEntry::make('mailing_address')->label('Address')->columnSpanFull()->placeholder('-'),
                            TextEntry::make('special_needs')->label('Special Needs')->columnSpanFull()->placeholder('-')->color('danger'),
                        ]),
                    ]),

                // 3. Pax & Extras
                Section::make('Passengers & Luggage')
                    ->icon('heroicon-m-users')
                    ->schema([
                        Grid::make(4)->schema([
                            TextEntry::make('adults')->label('Adults'),
                            TextEntry::make('children')->label('Children'),
                            TextEntry::make('total_passengers')->label('Total Pax')->weight('bold'),
                            TextEntry::make('luggage')->label('Luggage')->icon('heroicon-m-briefcase'),
                        ]),

                        Section::make('Extra Add-ons')
                            ->schema([
                                TextEntry::make('booster_seat_count')->label('Booster Seats')->visible(fn($record) => $record->booster_seat_count > 0),
                                TextEntry::make('infant_seat_count')->label('Infant Seats')->visible(fn($record) => $record->infant_seat_count > 0),
                                TextEntry::make('front_seat_count')->label('Front Facing')->visible(fn($record) => $record->front_seat_count > 0),
                                TextEntry::make('stopover_count')->label('Stopovers')->visible(fn($record) => $record->stopover_count > 0),
                                TextEntry::make('pet_count')->label('Pets')->visible(fn($record) => $record->pet_count > 0),
                            ])
                            ->columns(3)
                            ->compact()
                            ->visible(
                                fn($record) =>
                                $record->booster_seat_count > 0 ||
                                    $record->infant_seat_count > 0 ||
                                    $record->front_seat_count > 0 ||
                                    $record->stopover_count > 0 ||
                                    $record->pet_count > 0
                            ),

                        // 4. Status
                        Section::make('Status')
                            ->schema([
                                TextEntry::make('status')
                                    ->hiddenLabel()
                                    ->badge()
                                    ->size('lg')
                                    ->color(fn(string $state): string => match ($state) {
                                        'confirmed' => 'success',
                                        'completed' => 'primary',
                                        'cancelled' => 'danger',
                                        default => 'warning',
                                    }),
                                TextEntry::make('created_at')->label('Booked On')->dateTime('M d, Y h:i A')->color('gray'),
                            ]),
                    ]),

                // 2. Trip Details
                Section::make('Trip Details')
                    ->icon('heroicon-m-map')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('booking_no')->label('Booking Ref')->weight('bold')->copyable(),
                            TextEntry::make('trip_type')
                                ->badge()
                                ->formatStateUsing(fn(string $state): string => match ($state) {
                                    'fromAirport' => 'From Airport',
                                    'toAirport' => 'To Airport',
                                    'door-to-door' => 'Door to Door',
                                    default => $state,
                                })
                                ->color('info'),
                        ]),

                        Grid::make(2)->schema([
                            TextEntry::make('pickup_date')->date('l, d M Y')->placeholder('-'),
                            TextEntry::make('pickup_time')->time('h:i A')->placeholder('-'),
                        ]),

                        Section::make('Route')
                            ->schema([
                                TextEntry::make('pickup_address')->label('Pickup')->icon('heroicon-m-map-pin')->placeholder('-'),
                                TextEntry::make('dropoff_address')->label('Dropoff')->icon('heroicon-m-flag')->placeholder('-'),
                            ])->compact(),

                        Grid::make(3)->schema([
                            TextEntry::make('distance_miles')->label('Distance')->suffix(' miles')->placeholder('-'),
                            TextEntry::make('vehicle_type')->label('Vehicle')->placeholder('Any'),
                            TextEntry::make('vehicles_used')->label('Vehicles Used')->default(1),
                        ]),

                        // Flight Info (Only show if present)
                        Section::make('Flight Information')
                            ->schema([
                                TextEntry::make('airline_name')->label('Airline'),
                                TextEntry::make('flight_number')->label('Flight No'),
                            ])
                            ->visible(fn($record) => !empty($record->airline_name) || !empty($record->flight_number))
                            ->columns(2),
                    ]),




                // 5. Payment Information
                Section::make('Payment Breakdown')
                    ->icon('heroicon-m-currency-dollar')
                    ->schema([
                        TextEntry::make('total_fare')->label('Total Fare')->money('USD')->weight('bold')->size('lg'),

                        Grid::make(2)->schema([
                            TextEntry::make('paid_amount')->label('Paid')->money('USD')->placeholder('$0.00')->color('success'),
                            TextEntry::make('due_amount')->label('Due')->money('USD')->placeholder('$0.00')->color('danger'),
                        ]),

                        // Fare Breakdown Details (Collapsible)
                        Section::make('Detailed Breakdown')
                            ->collapsed()
                            ->schema([
                                Grid::make(2)->schema([
                                    TextEntry::make('estimated_fare')->label('Base Fare')->money('USD'),
                                    TextEntry::make('gratuity')->label('Gratuity')->money('USD'),
                                    TextEntry::make('pickup_tax')->label('Pickup Tax')->money('USD'),
                                    TextEntry::make('dropoff_tax')->label('Dropoff Tax')->money('USD'),
                                    TextEntry::make('parking_fee')->label('Parking')->money('USD'),
                                    TextEntry::make('toll_fee')->label('Toll')->money('USD'),
                                    TextEntry::make('surcharge_fee')->label('Surcharge')->money('USD'),
                                    TextEntry::make('extras_total')->label('Extras Total')->money('USD'),
                                ]),
                            ]),

                        TextEntry::make('payment_status')->badge()->color(fn(string $state): string => match ($state) {
                            'paid' => 'success',
                            'partial' => 'warning',
                            'pending' => 'danger',
                            default => 'gray',
                        }),

                        TextEntry::make('payment_method')->label('Method')->formatStateUsing(fn($state) => ucfirst($state))->icon('heroicon-m-credit-card'),
                        TextEntry::make('transaction_id')->label('Txn ID')->copyable()->fontFamily('mono')->placeholder('-'),

                        // Card Info
                        Grid::make(2)->schema([
                            TextEntry::make('card_brand')->label('Card Brand')->placeholder('-'),
                            TextEntry::make('card_last_four')->label('Last 4')->placeholder('****'),
                        ])->visible(fn($record) => !empty($record->card_last_four)),
                    ]),

                // 6. Billing Info
                Section::make('Billing Details')
                    ->icon('heroicon-m-receipt-percent')
                    ->schema([
                        TextEntry::make('card_holder_name')->label('Card Holder'),
                        TextEntry::make('billing_phone')->label('Phone'),
                        TextEntry::make('billing_address')->label('Address')->columnSpanFull(),
                        Grid::make(3)->schema([
                            TextEntry::make('billing_city')->label('City'),
                            TextEntry::make('billing_state')->label('State'),
                            TextEntry::make('billing_zip')->label('Zip'),
                        ]),
                    ])
                    ->collapsed(),
            ]);
    }
}
