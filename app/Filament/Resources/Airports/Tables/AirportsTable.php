<?php

namespace App\Filament\Resources\Airports\Tables;

use App\Models\Airport;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class AirportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn(Airport $record) => $record->address),

                TextColumn::make('short_name')
                    ->badge()
                    ->color('gray')
                    ->label('Short Name'),

                TextColumn::make('pickup_tax_fee')
                    ->money('USD')
                    ->label('Pickup Tax Fee')
                    ->sortable(),

                    TextColumn::make('dropoff_tax_fee')
                        ->money('USD')
                        ->label('Drop Tax Fee')
                        ->sortable(),

                    TextColumn::make('parking_fee')
                        ->money('USD')
                        ->label('Parking Fee')
                        ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Status'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
