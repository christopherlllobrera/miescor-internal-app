<?php

namespace App\Filament\Clusters\WorkOrder\PreventiveWorkOrders\Pages;

use App\Filament\Clusters\WorkOrder\PreventiveWorkOrders\PreventiveWorkOrderResource;
use App\Filament\Pages\Widgets\PreventiveWorkOrderOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPreventiveWorkOrders extends ListRecords
{
    protected static string $resource = PreventiveWorkOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Work Order'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PreventiveWorkOrderOverview::class,
        ];
    }
}
