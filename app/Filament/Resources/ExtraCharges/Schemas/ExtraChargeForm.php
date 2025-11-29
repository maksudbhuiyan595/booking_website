<?php

namespace App\Filament\Resources\ExtraCharges\Schemas;

use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExtraChargeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Extra Charge Details')
                            ->description('Details about the extra charge applicable to specific zip codes.')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Area Name')
                                    ->required()
                                    ->placeholder('Enter area name')
                                    ->columnSpanFull(),
                                TagsInput::make('zip_codes')
                                    ->label('Zip Codes')
                                    ->required()
                                    ->placeholder('Type zip code and hit Enter')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
                Group::make([
                        Section::make('Charges')
                            ->schema([
                                TextInput::make('price')
                                    ->label('Extra Charge')
                                    ->numeric()
                                    ->prefix('$')
                                    ->default(0)
                                    ->required(),

                                TextInput::make('toll_fee')
                                    ->label('Extra Toll Charge')
                                    ->numeric()
                                    ->prefix('$')
                                    ->default(0),

                                Toggle::make('is_active')
                                    ->label('Active Status')
                                    ->default(true)
                                    ->onColor('success'),
                            ]),
                    ])->columnSpan(['lg' => 1]),
            ]);
    }
}
