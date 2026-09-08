<?php

namespace App\Filament\Resources\ContractProponents;

use App\Filament\Resources\ContractProponents\Pages\CreateContractProponent;
use App\Filament\Resources\ContractProponents\Pages\EditContractProponent;
use App\Filament\Resources\ContractProponents\Pages\ListContractProponents;
use App\Filament\Resources\ContractProponents\Schemas\ContractProponentForm;
use App\Filament\Resources\ContractProponents\Tables\ContractProponentsTable;
use App\Models\ContractProponent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContractProponentResource extends Resource
{
    protected static ?string $model = ContractProponent::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Contract Management';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ContractProponentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContractProponentsTable::configure($table);
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
            'index' => ListContractProponents::route('/'),
            'create' => CreateContractProponent::route('/create'),
            'edit' => EditContractProponent::route('/{record}/edit'),
        ];
    }
}
