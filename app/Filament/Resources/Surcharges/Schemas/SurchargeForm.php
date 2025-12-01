<?php

namespace App\Filament\Resources\Surcharges\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class SurchargeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Surcharge Details')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Charge Name')
                                    ->placeholder('e.g. Night Fee / Christmas')
                                    ->required(),

                                Select::make('type')
                                    ->options([
                                        'time' => 'Time Based (Night/Rush Hour)',
                                        'date' => 'Date Based (Holiday/Event)',
                                    ])
                                    ->default('time')
                                    ->native(false)
                                    ->live()
                                    ->required(),

                                Grid::make(2)->schema([
                                    TextInput::make('price')
                                        ->label('Amount')
                                        ->numeric()
                                        ->required(),

                                    Toggle::make('is_percentage')
                                        ->label('Is Percentage (%)?')
                                        ->inline(false)
                                        ->onColor('warning'),
                                ]),
                            ]),
                    ])->columnSpan(['lg' => 1]),
            Group::make([
                        Section::make('Applicable Duration')
                            ->description('When should this charge apply?')
                            ->schema([
                                Group::make([
                                    TimePicker::make('start_time')
                                        ->label('Start Time')
                                        ->seconds(false)
                                        ->required(),
                                    TimePicker::make('end_time')
                                        ->label('End Time')
                                        ->seconds(false)
                                        ->required(),
                                ])
                                ->visible(fn (Get $get) => $get('type') === 'time'),

                                Group::make([
                                    DatePicker::make('start_date')
                                        ->label('Start Date')
                                        ->native(false)
                                        ->required(),
                                    DatePicker::make('end_date')
                                        ->label('End Date')
                                        ->native(false)
                                        ->required(),
                                ])
                                ->visible(fn (Get $get) => $get('type') === 'date'),

                                Toggle::make('is_active')
                                    ->label('Active Status')
                                    ->default(true)
                                    ->onColor('success'),
                            ]),
                    ])->columnSpan(['lg' => 1]),
            ]);

    }
}
