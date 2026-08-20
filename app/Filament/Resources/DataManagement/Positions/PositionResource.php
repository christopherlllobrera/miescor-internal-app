<?php

namespace App\Filament\Resources\DataManagement\Positions;

use App\Filament\Resources\DataManagement\Positions\Pages\CreatePosition;
use App\Filament\Resources\DataManagement\Positions\Pages\EditPosition;
use App\Filament\Resources\DataManagement\Positions\Pages\ListPositions;
use App\Filament\Resources\DataManagement\Positions\Schemas\PositionForm;
use App\Filament\Resources\DataManagement\Positions\Tables\PositionsTable;
use App\Models\Position;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class PositionResource extends Resource
{
    protected static ?string $model = Position::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Briefcase;

    public static function canAccess(): bool
    {
        $user = Auth::user();

        // Allow access if user has the 'superadmin' or 'Dispatcher' role
        return $user && ($user->hasRole('superadmin'));
    }

    protected static string|UnitEnum|null $navigationGroup = 'Data Management';

    protected static ?string $navigationLabel = 'Positions';

    protected static ?int $navigationSort = 4;

    protected static ?string $breadcrumb = 'Positions';

    protected static ?string $slug = 'positions';

    public static function form(Schema $schema): Schema
    {
        return PositionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PositionsTable::configure($table);
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
            'index' => ListPositions::route('/'),
            'create' => CreatePosition::route('/create'),
            'edit' => EditPosition::route('/{record}/edit'),
        ];
    }
}
