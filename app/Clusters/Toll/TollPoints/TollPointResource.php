<?php

namespace App\Filament\Clusters\Toll\TollPoints;

use App\Filament\Clusters\Toll\TollCluster;
use App\Filament\Clusters\Toll\TollPoints\Pages\CreateTollPoint;
use App\Filament\Clusters\Toll\TollPoints\Pages\EditTollPoint;
use App\Filament\Clusters\Toll\TollPoints\Pages\ListTollPoints;
use App\Filament\Clusters\Toll\TollPoints\Schemas\TollPointForm;
use App\Filament\Clusters\Toll\TollPoints\Tables\TollPointsTable;
use App\Models\TollPoint;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class TollPointResource extends Resource
{
    protected static ?string $model = TollPoint::class;

    // protected static string | UnitEnum | null $navigationGroup = 'Fleet Management';

    protected static ?string $cluster = TollCluster::class;

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return TollPointForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TollPointsTable::configure($table);
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
            'index' => ListTollPoints::route('/'),
            'create' => CreateTollPoint::route('/create'),
            'edit' => EditTollPoint::route('/{record}/edit'),
        ];
    }
}
