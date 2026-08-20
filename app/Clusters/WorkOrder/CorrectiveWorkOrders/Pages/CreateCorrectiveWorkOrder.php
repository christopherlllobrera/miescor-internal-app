<?php

namespace App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders\Pages;

use App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders\CorrectiveWorkOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCorrectiveWorkOrder extends CreateRecord
{
    protected static string $resource = CorrectiveWorkOrderResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
