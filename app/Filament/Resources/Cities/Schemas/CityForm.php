<?php

namespace App\Filament\Resources\Cities\Schemas;

use App\Models\City;
use Filament\Forms\Components\FileUpload;
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
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),

                            TextInput::make('slug')
                                ->label('URL Slug')
                                ->disabled()
                                ->dehydrated()
                                ->required()
                                ->unique(City::class, 'slug', ignoreRecord: true),

                            RichEditor::make('content')
                                ->label('Page Content')
                                ->fileAttachmentsDirectory('cities/content-images')
                                ->fileAttachmentsVisibility('public')
                                ->extraInputAttributes(['style' => 'min-height: 400px'])
                                ->toolbarButtons([
                                    'attachFiles',
                                    'blockquote',
                                    'bold',
                                    'bulletList',
                                    'codeBlock',
                                    'h2',
                                    'h3',
                                    'italic',
                                    'link',
                                    'orderedList',
                                    'redo',
                                    'strike',
                                    'underline',
                                    'undo',
                                    'alignJustify',
                                    'alignCenter',
                                    'textColor',
                                ])
                                ->columnSpanFull(),
                        ])->columnSpan(['lg' => 2]),

                    Section::make('Media & Visibility')
                        ->schema([
                            FileUpload::make('cover_image')
                                ->label('Cover Image')
                                ->image()
                                ->imageEditor()
                                ->directory('cities/covers')
                                ->required(),

                            Toggle::make('is_featured')
                                ->label('Is Featured City')
                                ->default(true)
                                ->onColor('success'),
                        ])->columnSpanFull(),
                        ]);

    }
}
