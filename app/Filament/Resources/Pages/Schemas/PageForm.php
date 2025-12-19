<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Models\Page;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Page Settings')
                    ->schema([
                        TextInput::make('route_name')
                            ->label('Route Identifier')
                            ->placeholder('e.g. home, about, contact')
                            ->helperText('Unique ID to call this page in frontend code.')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->unique(Page::class, 'route_name', ignoreRecord: true),

                        TextInput::make('slug')
                            ->label('URL Slug')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->unique(Page::class, 'slug', ignoreRecord: true),
                    ])->columnSpanFull(),
                Section::make('SEO Metadata')
                    ->description('Control how this page appears in search engines.')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(60)
                            ->placeholder('Best Limo Service in NY - MyCompany')
                            ->helperText('Leave empty to use default title.'),

                        Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->maxLength(160)
                            ->rows(3)
                            ->placeholder('Book luxury limo services...'),
                        Toggle::make('is_active')
                            ->label('Active')
                            // ->helperText('If inactive, this page will not be accessible on the website.')
                            ->default(true)
                            ->onColor('success'),
                    ])->columnSpanFull(),
            ]);
    }
}
