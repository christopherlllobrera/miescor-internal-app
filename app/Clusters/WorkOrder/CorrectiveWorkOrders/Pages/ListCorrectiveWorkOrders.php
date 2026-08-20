<?php

namespace App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders\Pages;

use App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders\CorrectiveWorkOrderResource;
use App\Filament\Pages\Widgets\CorrectiveWorkOrderOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCorrectiveWorkOrders extends ListRecords
{
    protected static string $resource = CorrectiveWorkOrderResource::class;

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
            CorrectiveWorkOrderOverview::class,
        ];
    }
}
