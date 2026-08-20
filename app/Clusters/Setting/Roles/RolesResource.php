<?php

namespace App\Filament\Clusters\Setting\Roles;

use App\Filament\Clusters\Setting\Roles\Pages\CreateRoles;
use App\Filament\Clusters\Setting\Roles\Pages\EditRoles;
use App\Filament\Clusters\Setting\Roles\Pages\ListRoles;
use App\Filament\Clusters\Setting\Roles\Schemas\RolesForm;
use App\Filament\Clusters\Setting\Roles\Tables\RolesTable;
use App\Filament\Clusters\Setting\SettingCluster;
use App\Models\Role as ModelsRole;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class RolesResource extends Resource
{
    protected static ?string $model = ModelsRole::class;

    protected static ?string $cluster = SettingCluster::class;

    protected static ?string $navigationLabel = 'Roles';

    protected static ?int $navigationSort = 2;

    protected static ?string $breadcrumb = 'Roles';

    public static function form(Schema $schema): Schema
    {
        return RolesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
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
            'index' => ListRoles::route('/'),
            'create' => CreateRoles::route('/create'),
            'edit' => EditRoles::route('/{record}/edit'),
        ];
    }
}
