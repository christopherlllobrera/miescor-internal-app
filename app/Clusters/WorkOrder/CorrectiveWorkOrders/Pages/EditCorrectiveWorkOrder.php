<?php

namespace App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders\Pages;

use App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders\CorrectiveWorkOrderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCorrectiveWorkOrder extends EditRecord
{
    protected static string $resource = CorrectiveWorkOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
