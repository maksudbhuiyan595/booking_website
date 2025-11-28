<?php

namespace App\Filament\Resources\ExtraCharges\Tables;

use App\Models\ExtraCharge;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ExtraChargesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->weight('bold')
                    ->label('Area Name'),
                TextColumn::make('zip_codes')
                    ->badge()
                    ->separator(',')
                    ->limitList(3)
                    ->label('Zip Codes')
                    ->tooltip(function (ExtraCharge $record): string {
                        if (is_array($record->zip_codes)) {
                            return implode(', ', $record->zip_codes);
                        }
                        return (string) $record->zip_codes;
                    }),

                TextColumn::make('price')
                    ->money('USD')
                    ->label('Extra Charge'),

                TextColumn::make('toll_fee')
                    ->money('USD')
                    ->label('Toll Fee'),

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
