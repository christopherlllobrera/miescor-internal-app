<?php

namespace App\Filament\Resources\Fleet\TroubleCalls;

use App\Filament\Resources\Fleet\TroubleCalls\Pages\ApprovalTroubleCall;
use App\Filament\Resources\Fleet\TroubleCalls\Pages\CreateTroubleCall;
use App\Filament\Resources\Fleet\TroubleCalls\Pages\EditTroubleCall;
use App\Filament\Resources\Fleet\TroubleCalls\Pages\ListTroubleCalls;
use App\Filament\Resources\Fleet\TroubleCalls\Schemas\TroubleCallForm;
use App\Filament\Resources\Fleet\TroubleCalls\Tables\TroubleCallsTable;
use App\Models\TroubleCall;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TroubleCallResource extends Resource
{
    protected static ?string $model = TroubleCall::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::PhoneArrowDownLeft;

    protected static string|UnitEnum|null $navigationGroup = 'Fleet Management';

    protected static ?string $navigationLabel = 'Trouble Call';

    protected static ?int $navigationSort = 3;

    protected static ?string $breadcrumb = 'Trouble Call';

    protected static ?string $slug = 'trouble-call';

    public static function form(Schema $schema): Schema
    {
        return TroubleCallForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TroubleCallsTable::configure($table);
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
            'index' => ListTroubleCalls::route('/'),
            'create' => CreateTroubleCall::route('/create'),
            'edit' => EditTroubleCall::route('/{record}/edit'),
            'approve' => ApprovalTroubleCall::route('/{record}/approval'),
        ];
    }
}
