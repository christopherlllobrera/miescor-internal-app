<?php

namespace App\Filament\Resources\ContractManagement\ContractProponents\Pages;
 
use App\Filament\Resources\ContractManagement\ContractProponents\ContractProponentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContractProponent extends EditRecord
{
    protected static string $resource = ContractProponentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
