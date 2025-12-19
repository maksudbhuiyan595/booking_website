<?php

namespace App\Filament\Resources\BlogPosts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class BlogPostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png')),

                TextColumn::make('title')
                    ->searchable()
                    ->weight('bold')
                    ->limit(50),

                TextColumn::make('published_at')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('tags')
                    ->badge()
                    ->separator(',')
                    ->limitList(2),

                ToggleColumn::make('is_published')->label('Status'),
            ])
            ->defaultSort('published_at', 'desc')
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
