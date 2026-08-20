<?php

namespace App\Filament\Clusters\WorkOrder\PreventiveWorkOrders\Pages;

use App\Filament\Clusters\WorkOrder\PreventiveWorkOrders\PreventiveWorkOrderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPreventiveWorkOrder extends EditRecord
{
    protected static string $resource = PreventiveWorkOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
