<?php

namespace App\Filament\Resources\EmployeePortal\DirectoryModules;

use App\Filament\Resources\EmployeePortal\DirectoryModules\Pages\CreateDirectoryModule;
use App\Filament\Resources\EmployeePortal\DirectoryModules\Pages\EditDirectoryModule;
use App\Filament\Resources\EmployeePortal\DirectoryModules\Pages\ListDirectoryModules;
use App\Filament\Resources\EmployeePortal\DirectoryModules\Schemas\DirectoryModuleForm;
use App\Filament\Resources\EmployeePortal\DirectoryModules\Tables\DirectoryModulesTable;
use App\Models\DirectoryModule;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class DirectoryModuleResource extends Resource
{
    protected static ?string $model = DirectoryModule::class;

    protected static string|UnitEnum|null $navigationGroup = 'Employee Portal';

    protected static ?string $navigationLabel = 'Directories';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return DirectoryModuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DirectoryModulesTable::configure($table);
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
            'index' => ListDirectoryModules::route('/'),
            'create' => CreateDirectoryModule::route('/create'),
            'edit' => EditDirectoryModule::route('/{record}/edit'),
        ];
    }
}
