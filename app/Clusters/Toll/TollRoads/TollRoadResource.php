<?php

namespace App\Filament\Clusters\Toll\TollRoads;

use App\Filament\Clusters\Toll\TollCluster;
use App\Filament\Clusters\Toll\TollRoads\Pages\CreateTollRoad;
use App\Filament\Clusters\Toll\TollRoads\Pages\EditTollRoad;
use App\Filament\Clusters\Toll\TollRoads\Pages\ListTollRoads;
use App\Filament\Clusters\Toll\TollRoads\Schemas\TollRoadForm;
use App\Filament\Clusters\Toll\TollRoads\Tables\TollRoadsTable;
use App\Models\TollRoad;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TollRoadResource extends Resource
{
    protected static ?string $model = TollRoad::class;

    // protected static string | UnitEnum | null $navigationGroup = 'Fleet Management';

    protected static ?string $cluster = TollCluster::class;

    protected static ?int $navigationSort = 6;

    // protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowLongUp;

    public static function form(Schema $schema): Schema
    {
        return TollRoadForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TollRoadsTable::configure($table);
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
            'index' => ListTollRoads::route('/'),
            'create' => CreateTollRoad::route('/create'),
            'edit' => EditTollRoad::route('/{record}/edit'),
        ];
    }
}
