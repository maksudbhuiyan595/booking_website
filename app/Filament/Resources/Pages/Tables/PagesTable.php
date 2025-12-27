<?php

namespace App\Filament\Resources\Pages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\View;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                 ImageColumn::make('cover_image')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png')),
                TextColumn::make('route_name')
                    ->badge()
                    ->color('info')
                    ->label('Route ID')
                    ->copyable()
                    ->copyMessage('Route ID copied!')
                    ->tooltip('Click to copy ID'),
                TextColumn::make('slug')
                    ->label('Page Link')
                    ->formatStateUsing(fn(string $state): string => url($state))
                    ->limit(20)
                    ->copyable()
                    ->copyableState(fn(string $state): string => url($state))
                    ->copyMessage('Full link copied to clipboard')
                    ->icon('heroicon-m-clipboard-document')
                    ->iconPosition('after')
                    ->color('primary')
                    ->tooltip('Click icon to copy full link'),

                ToggleColumn::make('is_active')->label('Status'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ViewAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
