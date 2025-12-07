<?php

namespace App\Filament\Resources\Airports\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AirportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Airport Information')
                            ->description('Basic details about the airport location.')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Airport Name')
                                    ->required()
                                    ->placeholder('Enter airport name')
                                    ->columnSpanFull(),
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('short_name')
                                            ->label('Short Code')
                                            ->placeholder('e.g. JFK')
                                            ->required(),

                                        Toggle::make('is_active')
                                            ->label('Active Status')
                                            ->default(true)
                                            ->inline(false)
                                            ->onColor('success'),
                                    ]),

                                TextInput::make('address')
                                    ->label('Airport Address')
                                    ->placeholder('Enter airport address')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
                Group::make()
                    ->schema([
                        Section::make('Tolls & Surcharges')
                            ->description('Extra charges added to the booking.')
                            ->schema([
                                TextInput::make('pickup_tax_fee')
                                    ->label('From Tax/Toll (Pickup)')
                                    // ->helperText('Fee applied when picking up FROM airport')
                                    ->numeric()
                                    ->prefix('$')
                                    ->default(0),

                                TextInput::make('dropoff_tax_fee')
                                    ->label('To Tax/Toll (Drop-off)')
                                    // ->helperText('Fee applied when dropping TO airport')
                                    ->numeric()
                                    ->prefix('$')
                                    ->default(0),

                                TextInput::make('parking_fee')
                                    ->label('Parking Charge')
                                    ->numeric()
                                    ->prefix('$')
                                    ->default(0),
                            ])
                            ->columns(1),
                    ])
                    ->columnSpan(['lg' => 1]),

            ])
            ->columns(['default' => 1, 'lg' => 2]);
    }
}
