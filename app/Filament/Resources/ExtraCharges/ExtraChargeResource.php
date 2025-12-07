<?php

namespace App\Filament\Resources\ExtraCharges;

use App\Filament\Resources\ExtraCharges\Pages\CreateExtraCharge;
use App\Filament\Resources\ExtraCharges\Pages\EditExtraCharge;
use App\Filament\Resources\ExtraCharges\Pages\ListExtraCharges;
use App\Filament\Resources\ExtraCharges\Schemas\ExtraChargeForm;
use App\Filament\Resources\ExtraCharges\Tables\ExtraChargesTable;
use App\Models\ExtraCharge;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExtraChargeResource extends Resource
{
    protected static ?string $model = ExtraCharge::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CurrencyDollar;
    protected static \UnitEnum|string|null $navigationGroup = 'Fleet Management';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return ExtraChargeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExtraChargesTable::configure($table);
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
            'index' => ListExtraCharges::route('/'),
            'create' => CreateExtraCharge::route('/create'),
            'edit' => EditExtraCharge::route('/{record}/edit'),
        ];
    }
}
