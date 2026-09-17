<?php

namespace App\Filament\Resources\ArchivedContracts\Pages;

use App\Filament\Resources\ArchivedContracts\ArchivedContractsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListArchivedContracts extends ListRecords
{
    protected static string $resource = ArchivedContractsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
