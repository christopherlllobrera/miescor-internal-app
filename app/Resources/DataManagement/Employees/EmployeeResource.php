<?php

namespace App\Filament\Resources\DataManagement\Employees;

use App\Filament\Resources\DataManagement\Employees\Pages\CreateEmployee;
use App\Filament\Resources\DataManagement\Employees\Pages\EditEmployee;
use App\Filament\Resources\DataManagement\Employees\Pages\ListEmployees;
use App\Filament\Resources\DataManagement\Employees\Schemas\EmployeeForm;
use App\Filament\Resources\DataManagement\Employees\Tables\EmployeesTable;
use App\Models\Employee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::User;

    // protected static ?string $recordTitleAttribute = 'Employee';

    public static function canAccess(): bool
    {
        $user = Auth::user();

        // Allow access if user has the 'superadmin' or 'Dispatcher' role
        return $user && ($user->hasRole('superadmin'));
    }

    protected static string|UnitEnum|null $navigationGroup = 'Data Management';

    protected static ?string $navigationLabel = 'Employees';

    protected static ?int $navigationSort = 3;

    protected static ?string $breadcrumb = 'Employee List';

    protected static ?string $slug = 'Employees';

    public static function form(Schema $schema): Schema
    {
        return EmployeeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeesTable::configure($table);
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
            'index' => ListEmployees::route('/'),
            'create' => CreateEmployee::route('/create'),
            'edit' => EditEmployee::route('/{record}/edit'),
        ];
    }
}
