<?php

namespace App\Filament\Resources\EmployeePortal\DepartmentModules;

use App\Filament\Resources\EmployeePortal\DepartmentModules\Pages\CreateDepartmentModule;
use App\Filament\Resources\EmployeePortal\DepartmentModules\Pages\EditDepartmentModule;
use App\Filament\Resources\EmployeePortal\DepartmentModules\Pages\ListDepartmentModules;
use App\Filament\Resources\EmployeePortal\DepartmentModules\Schemas\DepartmentModuleForm;
use App\Filament\Resources\EmployeePortal\DepartmentModules\Tables\DepartmentModulesTable;
use App\Models\DepartmentModule;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class DepartmentModuleResource extends Resource
{
    protected static ?string $model = DepartmentModule::class;

    protected static string|UnitEnum|null $navigationGroup = 'Employee Portal';

    protected static ?string $navigationLabel = 'Department Pages';

    protected static ?int $navigationSort = 1;

    protected static ?string $breadcrumb = 'Department Pages';

    protected static ?string $slug = 'department-pages';

    public static function form(Schema $schema): Schema
    {
        return DepartmentModuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DepartmentModulesTable::configure($table);
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
            'index' => ListDepartmentModules::route('/'),
            'create' => CreateDepartmentModule::route('/create'),
            'edit' => EditDepartmentModule::route('/{record}/edit'),
        ];
    }
}
