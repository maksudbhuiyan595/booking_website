<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Vehicle Details')
                            ->description('General information regarding the vehicle.')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->placeholder('e.g. Luxury Sedan')
                                    ->columnSpanFull(),

                                Grid::make(2)->schema([
                                    TextInput::make('capacity_passenger')
                                        ->numeric()
                                        ->label('Passenger Capacity')
                                        ->prefixIcon('heroicon-m-users')
                                        ->required(),

                                    TextInput::make('capacity_luggage')
                                        ->numeric()
                                        ->label('Luggage Capacity')
                                        ->prefixIcon('heroicon-m-briefcase')
                                        ->required(),
                                ]),

                                TagsInput::make('features')
                                    ->placeholder('Add features (e.g. WiFi, AC)')
                                    ->columnSpanFull(),
                            ]),

                        Section::make('Pricing Configuration')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('base_fare')
                                        ->numeric()
                                        ->prefix('$')
                                        ->label('Base Fare')
                                        ->required(),

                                    TextInput::make('min_fare')
                                        ->numeric()
                                        ->prefix('$')
                                        ->label('Minimum Fare')
                                        ->required(),
                                ]),

                                // DYNAMIC SLABS (Repeater)
                                Repeater::make('slabs')
                                    ->label('Distance Slabs (Tiered Pricing)')
                                    ->schema([
                                        TextInput::make('start_mile')
                                            ->numeric()
                                            ->required()
                                            ->label('Start Mile'),

                                        TextInput::make('end_mile')
                                            ->numeric()
                                            ->required()
                                            ->label('End Mile'),

                                        TextInput::make('price')
                                            ->numeric()
                                            ->prefix('$')
                                            ->required()
                                            ->label('Price/Mile'),
                                    ])
                                    ->columns(3)
                                    ->addActionLabel('Add New Slab')
                                    ->defaultItems(1)
                                    ->reorderableWithButtons()
                                    ->collapsible(),
                            ]),
                    ])->columnSpan(['lg' => 2]),

                // Layout: Right Side (Sidebar)
                Group::make()
                    ->schema([
                        Section::make('Visuals & Settings')
                            ->schema([
                                // Modern Image Upload with Editor
                                FileUpload::make('image')
                                    ->image()
                                    ->imageEditor() // Crop/Resize feature
                                    ->imagePreviewHeight('200')
                                    ->disk('public')
                                    ->directory('vehicles')
                                    ->columnSpanFull(),

                                ColorPicker::make('color')
                                    ->label('Vehicle Color'),

                                Section::make('Options')
                                    ->schema([
                                        Toggle::make('has_extra_seat')
                                            ->label('Has Child Seat')
                                            ->onColor('success'),

                                        Toggle::make('is_active')
                                            ->label('Available for Booking')
                                            ->default(true)
                                            ->onColor('success'),
                                    ])->compact(),
                            ])
                    ])->columnSpan(['lg' => 1]),
            ])
            ->columns(3); // Main Grid Layout
    }
}
