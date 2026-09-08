<?php

namespace App\Filament\Resources\ContractProponents\Pages;

use App\Filament\Resources\ContractProponents\ContractProponentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContractProponents extends ListRecords
{
    protected static string $resource = ContractProponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
