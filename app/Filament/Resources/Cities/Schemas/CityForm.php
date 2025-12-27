<?php

namespace App\Filament\Resources\Cities\Schemas;

use App\Models\City;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('City Details')
                    ->schema([
                        TextInput::make('name')
                            ->label('City Name')
                            ->required()
                            ->live(onBlur: true),

                        TextInput::make('url')
                            ->label('URL Slug')
                            ->dehydrated()
                            ->required()
                            ->unique(City::class, 'url', ignoreRecord: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('url_slug', Str::slug($state))),
                    ])->columnSpanFull(),
            ]);
    }
}
