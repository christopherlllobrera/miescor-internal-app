<?php

namespace App\Filament\Resources\Fleet\Vehicles;

use App\Filament\Resources\Fleet\Vehicles\Pages\CreateVehicle;
use App\Filament\Resources\Fleet\Vehicles\Pages\EditVehicle;
use App\Filament\Resources\Fleet\Vehicles\Pages\ListVehicles;
use App\Filament\Resources\Fleet\Vehicles\Schemas\VehicleForm;
use App\Filament\Resources\Fleet\Vehicles\Tables\VehiclesTable;
use App\Models\Vehicles;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicles::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Truck;

    protected static string|UnitEnum|null $navigationGroup = 'Fleet Management';

    protected static ?string $navigationLabel = 'Vehicles';

    protected static ?int $navigationSort = 7;

    protected static ?string $breadcrumb = 'Vehicles';

    protected static ?string $slug = 'vehicles';

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

    public static function getPages(): array
    {
        return [
            'index' => ListVehicles::route('/'),
            'create' => CreateVehicle::route('/create'),
            'edit' => EditVehicle::route('/{record}/edit'),
        ];
    }
}
