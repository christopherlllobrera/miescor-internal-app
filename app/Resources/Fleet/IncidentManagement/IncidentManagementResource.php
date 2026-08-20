<?php

namespace App\Filament\Resources\Fleet\IncidentManagement;

use App\Filament\Resources\Fleet\IncidentManagement\Pages\ApprovalIncident;
use App\Filament\Resources\Fleet\IncidentManagement\Pages\CreateIncidentManagement;
use App\Filament\Resources\Fleet\IncidentManagement\Pages\EditIncidentManagement;
use App\Filament\Resources\Fleet\IncidentManagement\Pages\ListIncidentManagement;
use App\Filament\Resources\Fleet\IncidentManagement\Schemas\IncidentManagementForm;
use App\Filament\Resources\Fleet\IncidentManagement\Tables\IncidentManagementTable;
use App\Models\IncidentManagement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class IncidentManagementResource extends Resource
{
    protected static ?string $model = IncidentManagement::class;

    protected static string|UnitEnum|null $navigationGroup = 'Fleet Management';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ExclamationTriangle;

    protected static ?string $navigationLabel = 'Incident';

    protected static ?int $navigationSort = 2;

    protected static ?string $breadcrumb = 'Incident';

    protected static ?string $slug = 'incident';

    public static function form(Schema $schema): Schema
    {
        return IncidentManagementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IncidentManagementTable::configure($table);
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
            'index' => ListIncidentManagement::route('/'),
            'create' => CreateIncidentManagement::route('/create'),
            'edit' => EditIncidentManagement::route('/{record}/edit'),
            'approve' => ApprovalIncident::route('/{record}/approval'),
        ];
    }
}
