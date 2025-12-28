<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use App\Models\BlogPost;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Article Content')
                    ->schema([
                        TextInput::make('title')
                            ->label('Article Title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),

                        TextInput::make('slug')
                            ->dehydrated()
                            ->required()
                            ->unique(BlogPost::class, 'slug', ignoreRecord: true),

                        RichEditor::make('content')
                            ->label('Body')
                            ->fileAttachmentsDirectory('blog-images')
                            ->fileAttachmentsVisibility('public')
                            ->extraInputAttributes(['style' => 'min-height: 400px'])
                            ->required()
                            ->columnSpanFull(),
                    ])->columnSpanFull(),
                Section::make('Publishing')
                    ->schema([
                        FileUpload::make('thumbnail')
                            ->disk('public')
                            ->label('Feature Image')
                            ->image()
                            ->imageEditor()
                            ->directory('blog-thumbnails')
                            ->required(),

                        DatePicker::make('published_at')
                            ->label('Publish Date')
                            ->default(now()),

                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(true)
                            ->onColor('success'),
                    ])->columnSpanFull(),
                Section::make('SEO Settings')
                    ->description('Optimize this post for search engines.')
                    ->collapsed()
                    ->schema([
                        TextInput::make('meta_title')->label('Meta Title (Optional)'),
                        Textarea::make('meta_description')->label('Meta Description')->rows(3),
                        TagsInput::make('tags')->label('Keywords / Tags'),
                    ])->columnSpanFull(),
            ])->columns(['lg' => 2]);
    }
}
