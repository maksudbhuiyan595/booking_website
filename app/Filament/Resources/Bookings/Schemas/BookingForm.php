<?php

namespace App\Filament\Resources\Bookings\Schemas;

use App\Models\Airport;
use App\Models\Booking;
use Carbon\Carbon;
use Dom\Text;
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
                            ->default(function () {
                                $lastBookingNo = Booking::max('booking_no');

                                if (!$lastBookingNo) {
                                    return 'BLAT-1001';
                                }
                                $number = (int) str_replace('BLAT-', '', $lastBookingNo);

                                return 'BLAT-' . ($number + 1);
                            })
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->unique(Booking::class, 'booking_no', ignoreRecord: true),
                        Select::make('trip_type')
                            ->options([
                                'fromAirport' => 'From Airport',
                                'toAirport' => 'To Airport',
                                'door-to-door' => 'Door to Door',
                            ])->required()
                            ->live(),

                        DatePicker::make('pickup_date')
                            ->required()
                            ->native(false)
                            ->minDate(now()->timezone(config('app.timezone'))->startOfDay())
                            ->closeOnDateSelection()
                            ->live(),

                        TimePicker::make('pickup_time')
                            ->required()
                            ->seconds(false)
                            ->rule(fn (Get $get) => function (string $attribute, $value, \Closure $fail) use ($get) {
                                $date = $get('pickup_date');
                                if ($date) {
                                    $selectedDateTime = Carbon::parse($date . ' ' . $value, config('app.timezone'));
                                    if ($selectedDateTime->isPast()) {
                                        $fail('The pickup time cannot be in the past.');
                                    }
                                }
                            })
                            ->live(),

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

                        TextInput::make('distance_miles')
                            ->numeric()
                            ->suffix('miles')
                            ->default(0)
                            ->formatStateUsing(fn($state) => $state == 0 ? null : $state)
                            ->dehydrateStateUsing(fn($state) => $state ?? 0),
                        TextInput::make('vehicle_type'),
                        TextInput::make('flight_number'),

                    ])->columns(2)
                    ->columnSpanFull(),


                Section::make('Customer Details')
                    ->schema([
                        TextInput::make('passenger_name')
                            ->default('Customer')
                            ->formatStateUsing(fn($state) => $state == 'Customer' ? null : $state)
                            ->dehydrateStateUsing(fn($state) => $state ?? 'Customer'),
                        TextInput::make('passenger_phone')->required(),
                        TextInput::make('passenger_email')->email()->required(),
                        TextInput::make('total_passengers')->numeric(),
                        TextInput::make('children')->numeric(),
                        TextInput::make('luggage')->numeric(),
                    ])->columns(3)
                    ->columnSpanFull(),

                Section::make('Payment & Status')
                    ->schema([
                        TextInput::make('total_fare')->prefix('$')->numeric()->required(),
                        TextInput::make('paid_amount')->prefix('$')
                            ->numeric()
                            ->default(0)
                            ->formatStateUsing(fn($state) => $state == 0 ? null : $state)
                            ->dehydrateStateUsing(fn($state) => $state ?? 0),
                        TextInput::make('due_amount')->prefix('$')
                            ->numeric()
                            ->default(0)
                            ->formatStateUsing(fn($state) => $state == 0 ? null : $state)
                            ->dehydrateStateUsing(fn($state) => $state ?? 0),

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
