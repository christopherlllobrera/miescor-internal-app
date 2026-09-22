<?php

namespace App\Filament\Resources\ContractManagement\ContractProponents\Pages;

use App\Filament\Resources\ContractManagement\ContractProponents\ContractProponentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContractProponent extends CreateRecord
{
    protected static string $resource = ContractProponentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
