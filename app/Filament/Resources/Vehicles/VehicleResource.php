<?php

namespace App\Filament\Resources\Vehicles;

use App\Filament\Resources\Vehicles\Pages\CreateVehicle;
use App\Filament\Resources\Vehicles\Pages\EditVehicle;
use App\Filament\Resources\Vehicles\Pages\ListVehicles;
use App\Filament\Resources\Vehicles\Schemas\VehicleForm;
use App\Filament\Resources\Vehicles\Tables\VehiclesTable;
use App\Models\Vehicle;
use BackedEnum;
use Filament\Infolists\Components\ColorEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $recordTitleAttribute = 'name';
    protected static int $globalSearchResultsLimit = 5;
    protected static ?int $navigationSort = 1;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Truck;

    public static function form(Schema $schema): Schema
    {
        return VehicleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VehiclesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Vehicle Overview')
                            ->schema([
                                Grid::make([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 3,
                                    'lg' => 1,
                                    'xl' => 1,
                                ])
                                    ->schema([
                                        ImageEntry::make('image')
                                            ->hiddenLabel()
                                            ->circular()
                                            ->imageSize(100)
                                            ->extraAttributes(['class' => 'justify-center']),
                                        Group::make([
                                            TextEntry::make('name')
                                                ->hiddenLabel()
                                                ->weight('bold')
                                                ->size('lg'),

                                            ColorEntry::make('color')
                                                ->label('Vehicle Color'),
                                        ])->columnSpan(1),

                                        Group::make([
                                            IconEntry::make('is_active')
                                                ->label('Active Status')
                                                ->boolean(),

                                            IconEntry::make('has_extra_seat')
                                                ->label('Child Seat')
                                                ->boolean(),
                                        ])->columnSpan(1),
                                    ]),
                            ]),

                        Section::make('Capacity Details')
                            ->schema([
                                Grid::make([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 3,
                                    'lg' => 2,
                                    'xl' => 2,
                                ])->schema([
                                    TextEntry::make('capacity_passenger')
                                        ->label('Passengers')
                                        ->icon('heroicon-m-users'),
                                    TextEntry::make('capacity_luggage')
                                        ->label('Luggage')
                                        ->icon('heroicon-m-briefcase'),
                                    TextEntry::make('features')
                                        ->badge()
                                        ->separator(','),
                                ]),
                            ])->compact(),
                    ]),

                Group::make()
                    ->schema([
                        Section::make('Pricing Structure')
                            ->schema([
                                Grid::make([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 2,
                                    'lg' => 2,
                                    'xl' => 2,
                                ])->schema([
                                    TextEntry::make('base_fare')->money('USD')->label('Base Fare'),
                                    TextEntry::make('min_fare')->money('USD')->label('Minimum Fare'),
                                ]),
                                RepeatableEntry::make('slabs')
                                    ->label('Distance Pricing Slabs')
                                    ->schema([
                                        Grid::make([
                                            'default' => 1,
                                            'sm' => 3,
                                            'md' => 3,
                                            'lg' => 3,
                                            'xl' => 3,
                                        ])->schema([
                                            TextEntry::make('start_mile')->label('Start (Mi)'),
                                            TextEntry::make('end_mile')->label('End (Mi)'),
                                            TextEntry::make('price')->money('USD')->label('Price/Mi')
                                                ->weight('bold')
                                                ->color('success'),
                                        ]),
                                    ])
                                    ->grid(1)
                                    ->columnSpanFull()
                                    ->contained(false),
                            ]),
                    ])->columnSpan(2)
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVehicles::route('/'),
            'create' => CreateVehicle::route('/create'),
            'edit' => EditVehicle::route('/{record}/edit'),
        ];
    }
}
