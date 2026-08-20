<?php

namespace App\Filament\Clusters\Setting\Permissions;

use App\Filament\Clusters\Setting\Permissions\Pages\CreatePermission;
use App\Filament\Clusters\Setting\Permissions\Pages\EditPermission;
use App\Filament\Clusters\Setting\Permissions\Pages\ListPermissions;
use App\Filament\Clusters\Setting\Permissions\Schemas\PermissionForm;
use App\Filament\Clusters\Setting\Permissions\Tables\PermissionsTable;
use App\Filament\Clusters\Setting\SettingCluster;
use App\Models\Permission as ModelsPermission;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PermissionResource extends Resource
{
    protected static ?string $model = ModelsPermission::class;

    protected static ?string $cluster = SettingCluster::class;

    protected static ?string $navigationLabel = 'Permission';

    protected static ?int $navigationSort = 3;

    protected static ?string $breadcrumb = 'Permission';

    public static function form(Schema $schema): Schema
    {
        return PermissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PermissionsTable::configure($table);
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
            'index' => ListPermissions::route('/'),
            // 'create' => CreatePermission::route('/create'),
            // 'edit' => EditPermission::route('/{record}/edit'),
        ];
    }
}
