<?php

namespace App\Filament\Resources\DataManagement\Departments;

use App\Filament\Resources\DataManagement\Departments\Pages\CreateDepartment;
use App\Filament\Resources\DataManagement\Departments\Pages\EditDepartment;
use App\Filament\Resources\DataManagement\Departments\Pages\ListDepartments;
use App\Filament\Resources\DataManagement\Departments\Schemas\DepartmentForm;
use App\Filament\Resources\DataManagement\Departments\Tables\DepartmentsTable;
use App\Models\Department;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static string|UnitEnum|null $navigationGroup = 'Data Management';

    public static function canAccess(): bool
    {
        $user = Auth::user();

        // Allow access if user has the 'superadmin' or 'Dispatcher' role
        return $user && ($user->hasRole('superadmin'));
    }

    protected static ?string $navigationLabel = 'Departments';

    protected static ?int $navigationSort = 1;

    protected static ?string $breadcrumb = 'Department';

    protected static ?string $slug = 'department';

    public static function form(Schema $schema): Schema
    {
        return DepartmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DepartmentsTable::configure($table);
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
            'index' => ListDepartments::route('/'),
            'create' => CreateDepartment::route('/create'),
            'edit' => EditDepartment::route('/{record}/edit'),
        ];
    }
}
