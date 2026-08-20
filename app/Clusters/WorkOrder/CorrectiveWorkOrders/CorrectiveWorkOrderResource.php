<?php

namespace App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders;

use App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders\Pages\CreateCorrectiveWorkOrder;
use App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders\Pages\EditCorrectiveWorkOrder;
use App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders\Pages\ListCorrectiveWorkOrders;
use App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders\Schemas\CorrectiveWorkOrderForm;
use App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders\Tables\CorrectiveWorkOrdersTable;
use App\Filament\Clusters\WorkOrder\WorkOrderCluster;
use App\Models\CorrectiveWorkOrder;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class CorrectiveWorkOrderResource extends Resource
{
    protected static ?string $model = CorrectiveWorkOrder::class;

    protected static string|UnitEnum|null $navigationGroup = 'Fleet Management';

    protected static ?string $cluster = WorkOrderCluster::class;

    protected static ?string $navigationLabel = 'Corrective Work Order';

    protected static ?int $navigationSort = 10;

    protected static ?string $breadcrumb = 'Corrective Work Order';

    protected static ?string $slug = 'corrective-work-order';

    public static function form(Schema $schema): Schema
    {
        return CorrectiveWorkOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CorrectiveWorkOrdersTable::configure($table);
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
            'index' => ListCorrectiveWorkOrders::route('/'),
            'create' => CreateCorrectiveWorkOrder::route('/create'),
            'edit' => EditCorrectiveWorkOrder::route('/{record}/edit'),
        ];
    }
}
