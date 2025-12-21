<?php

namespace App\Filament\Resources\Bookings\Schemas;

use App\Models\Airport;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Trip Information')
                    ->schema([
                        TextInput::make('booking_no')
                        ->default(fn() => 'BK-' . strtoupper(uniqid()))
                        ->disabled()
                        ->dehydrated(),
                        Select::make('trip_type')
                            ->options([
                                'fromAirport' => 'From Airport',
                                'toAirport' => 'To Airport',
                                'point-to-point' => 'Point to Point',
                            ])->required()
                            ->live(),

                        DatePicker::make('pickup_date')
                        ->required()
                        ->native(false)
                        ->minDate(now()),
                        TimePicker::make('pickup_time')->required(),

                        Select::make('pickup_address')
                            ->label('Pickup Airport')
                            ->options(Airport::where('is_active', true)->pluck('name', 'name'))
                            ->searchable()
                            ->required()
                            ->visible(fn(Get $get) => $get('trip_type') === 'fromAirport'),
                        TextInput::make('pickup_address')
                            ->label('Pickup Address')
                            ->required()
                            ->columnSpanFull()
                            ->hidden(fn(Get $get) => $get('trip_type') === 'fromAirport'),


                        // --- DROPOFF LOCATION LOGIC ---
                        Select::make('dropoff_address')
                            ->label('Dropoff Airport')
                            ->options(Airport::where('is_active', true)->pluck('name', 'name'))
                            ->searchable()
                            ->required()
                            ->visible(fn(Get $get) => $get('trip_type') === 'toAirport'),

                        TextInput::make('dropoff_address')
                            ->label('Dropoff Address')
                            ->required()
                            ->columnSpanFull()
                            ->hidden(fn(Get $get) => $get('trip_type') === 'toAirport'),

                        TextInput::make('distance')->numeric()->suffix('miles'),
                        TextInput::make('vehicle_type'),

                    ])->columns(2)
                    ->columnSpanFull(),


                Section::make('Customer Details')
                    ->schema([
                        TextInput::make('passenger_name')->required(),
                        TextInput::make('passenger_phone')->required(),
                        TextInput::make('passenger_email')->email()->required(),
                    ])->columns(3)
                    ->columnSpanFull(),

                Section::make('Payment & Status')
                    ->schema([
                        TextInput::make('total_fare')->prefix('$')->numeric()->required(),
                        TextInput::make('paid_amount')->prefix('$')->numeric(),
                        TextInput::make('due_amount')->prefix('$')->numeric(),

                        Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'partial' => 'Partial',
                                'paid' => 'Paid',
                            ])->default('pending'),

                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])->default('confirmed')->required(),
                    ])->columns(2)
                    ->columnSpanFull()
            ]);
    }
}
