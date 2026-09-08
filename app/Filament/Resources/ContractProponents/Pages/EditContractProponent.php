<?php

namespace App\Filament\Resources\ContractProponents\Pages;

use App\Filament\Resources\ContractProponents\ContractProponentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContractProponent extends EditRecord
{
    protected static string $resource = ContractProponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
