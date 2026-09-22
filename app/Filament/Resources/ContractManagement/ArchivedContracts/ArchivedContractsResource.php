<?php

namespace App\Filament\Resources\ContractManagement\ArchivedContracts;

use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\ContractManagement\ArchivedContracts\Pages\ListArchivedContracts;
use App\Filament\Resources\ContractManagement\Contracts\Schemas\ContractForm;
use App\Filament\Resources\ContractManagement\ArchivedContracts\Tables\ArchivedContractsTable;
use App\Models\Contract;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

use BackedEnum;
use UnitEnum;

class ArchivedContractsResource extends Resource
{
    protected static ?string $model = Contract::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-archive-box';
    
    protected static string|UnitEnum|null $navigationGroup = 'Contract Management';

    protected static ?int $navigationSort = 3;
    
    protected static ?string $navigationLabel = 'Archived Contracts';

    public static function form(Schema $schema): Schema
    {
        return ContractForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ArchivedContractsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListArchivedContracts::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status', 'executed');
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
