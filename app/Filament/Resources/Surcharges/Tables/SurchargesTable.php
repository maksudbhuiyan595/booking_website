<?php

namespace App\Filament\Resources\Surcharges\Tables;

use App\Models\Surcharge;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class SurchargesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Surcharge $record) => $record->type === 'time' ? 'Time Based' : 'Holiday/Date Based'),

                TextColumn::make('price')
                    ->formatStateUsing(fn (Surcharge $record) => $record->is_percentage ? "{$record->price}%" : "\${$record->price}")
                    ->label('Amount')
                    ->badge()
                    ->color('info'),

                TextColumn::make('duration')
                    ->label('Applied When')
                    ->getStateUsing(function (Surcharge $record) {
                        if ($record->type === 'time') {
                            return \Carbon\Carbon::parse($record->start_time)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($record->end_time)->format('h:i A');
                        }
                        return $record->start_date->format('M d') . ' - ' . $record->end_date->format('M d');
                    })
                    ->size('sm'),

                ToggleColumn::make('is_active')->label('Status'),
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
