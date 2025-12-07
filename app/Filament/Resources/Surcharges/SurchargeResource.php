<?php

namespace App\Filament\Resources\Surcharges;

use App\Filament\Resources\Surcharges\Pages\CreateSurcharge;
use App\Filament\Resources\Surcharges\Pages\EditSurcharge;
use App\Filament\Resources\Surcharges\Pages\ListSurcharges;
use App\Filament\Resources\Surcharges\Schemas\SurchargeForm;
use App\Filament\Resources\Surcharges\Tables\SurchargesTable;
use App\Models\Surcharge;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SurchargeResource extends Resource
{
    protected static ?string $model = Surcharge::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;
    protected static \UnitEnum|string|null $navigationGroup = 'Fleet Management';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return SurchargeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SurchargesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSurcharges::route('/'),
            'create' => CreateSurcharge::route('/create'),
            'edit' => EditSurcharge::route('/{record}/edit'),
        ];
    }
}
