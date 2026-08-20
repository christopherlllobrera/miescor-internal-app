<?php

namespace App\Filament\Clusters\WorkOrder\PreventiveWorkOrders\Pages;

use App\Filament\Clusters\WorkOrder\PreventiveWorkOrders\PreventiveWorkOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePreventiveWorkOrder extends CreateRecord
{
    protected static string $resource = PreventiveWorkOrderResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
