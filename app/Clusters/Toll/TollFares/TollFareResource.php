<?php

namespace App\Filament\Clusters\Toll\TollFares;

use App\Filament\Clusters\Toll\TollCluster;
use App\Filament\Clusters\Toll\TollFares\Pages\CreateTollFare;
use App\Filament\Clusters\Toll\TollFares\Pages\EditTollFare;
use App\Filament\Clusters\Toll\TollFares\Pages\ListTollFares;
use App\Filament\Clusters\Toll\TollFares\Schemas\TollFareForm;
use App\Filament\Clusters\Toll\TollFares\Tables\TollFaresTable;
use App\Models\TollFare;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TollFareResource extends Resource
{
    protected static ?string $model = TollFare::class;

    // protected static string | UnitEnum | null $navigationGroup = 'Fleet Management';
    protected static ?string $cluster = TollCluster::class;

    protected static ?int $navigationSort = 6;

    // protected static string|BackedEnum|null $navigationIcon = Heroicon::CurrencyDollar;

    public static function form(Schema $schema): Schema
    {
        return TollFareForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TollFaresTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTollFares::route('/'),
            'create' => CreateTollFare::route('/create'),
            'edit' => EditTollFare::route('/{record}/edit'),
        ];
    }
}
