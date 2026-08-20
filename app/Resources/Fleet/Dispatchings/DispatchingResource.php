<?php

namespace App\Filament\Resources\Fleet\Dispatchings;

use App\Filament\Resources\Fleet\Dispatchings\Pages\CreateDispatching;
use App\Filament\Resources\Fleet\Dispatchings\Pages\EditDispatching;
use App\Filament\Resources\Fleet\Dispatchings\Pages\ListDispatchings;
use App\Filament\Resources\Fleet\Dispatchings\Pages\ViewDispatching;
use App\Filament\Resources\Fleet\Dispatchings\Pages\ViewDispatchingRoute;
use App\Filament\Resources\Fleet\Dispatchings\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\Fleet\Dispatchings\RelationManagers\FuelRelationManager;
use App\Filament\Resources\Fleet\Dispatchings\RelationManagers\OdometerRelationManager;
use App\Filament\Resources\Fleet\Dispatchings\RelationManagers\TollsRelationManager;
use App\Filament\Resources\Fleet\Dispatchings\Schemas\DispatchingForm;
use App\Filament\Resources\Fleet\Dispatchings\Tables\DispatchingsTable;
use App\Models\Dispatchings;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class DispatchingResource extends Resource
{
    protected static ?string $model = Dispatchings::class;

    protected static string|UnitEnum|null $navigationGroup = 'Fleet Management';

    protected static ?string $navigationLabel = 'Dispatchings';

    protected static ?int $navigationSort = 1;

    protected static ?string $breadcrumb = 'Dispatchings';

    protected static ?string $slug = 'Dispatchings';

    public static function form(Schema $schema): Schema
    {
        return DispatchingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DispatchingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            OdometerRelationManager::class,
            FuelRelationManager::class,
            TollsRelationManager::class,
            ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDispatchings::route('/'),
            'create' => CreateDispatching::route('/create'),
            'edit' => EditDispatching::route('/{record}/edit'),
            'view' => ViewDispatching::route('/{record}'),
            'view-route' => ViewDispatchingRoute::route('/{record}/route'),
            // 'view-route' => ViewDispatchingRoute::route('/{record}/route'),
        ];
    }
}
