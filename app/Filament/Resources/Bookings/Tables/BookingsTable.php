<?php

namespace App\Filament\Resources\Bookings\Tables;

use App\Models\Booking;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\View;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking_no')->searchable()->weight('bold'),

                TextColumn::make('pickup_date')->date('M d, Y')->sortable(),
                TextColumn::make('pickup_time')->time('h:i A')->sortable(),

                TextColumn::make('passenger_name')->searchable()
                    ->description(fn(Booking $record) => $record->passenger_phone),

                TextColumn::make('trip_type')->badge()->color('info'),

                TextColumn::make('total_fare')->money('USD')->weight('bold'),

                SelectColumn::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])->sortable(),
            ])
            ->defaultSort('pickup_date', 'desc')
            ->filters([
                SelectFilter::make('status'),
                Filter::make('today')
                    ->label("Today's Trips")
                    ->query(fn(Builder $query) => $query->whereDate('pickup_date', now())),
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make()
                    ->modalHeading('Booking Details')
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(null)
            ->recordAction(ViewAction::class);

    }
}
