<?php

namespace App\Filament\Resources\AttendanceAuthorizationForms;

use App\Filament\Resources\AttendanceAuthorizationForms\Pages\CreateAttendanceAuthorizationForm;
use App\Filament\Resources\AttendanceAuthorizationForms\Pages\EditAttendanceAuthorizationForm;
use App\Filament\Resources\AttendanceAuthorizationForms\Pages\ListAttendanceAuthorizationForms;
use App\Filament\Resources\AttendanceAuthorizationForms\Schemas\AttendanceAuthorizationFormForm;
use App\Filament\Resources\AttendanceAuthorizationForms\Tables\AttendanceAuthorizationFormsTable;
use App\Models\AttendanceAuth;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AttendanceAuthorizationFormResource extends Resource
{
    protected static ?string $model = AttendanceAuth::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Payroll Self Service';

    protected static ?string $navigationLabel = 'Attendance Authorization';

    protected static ?string $breadcrumb = 'Attendance Authorization';

    public static function form(Schema $schema): Schema
    {
        return AttendanceAuthorizationFormForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendanceAuthorizationFormsTable::configure($table);
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
            'index' => ListAttendanceAuthorizationForms::route('/'),
            'create' => CreateAttendanceAuthorizationForm::route('/create'),
            'edit' => EditAttendanceAuthorizationForm::route('/{record}/edit'),
        ];
    }
}
