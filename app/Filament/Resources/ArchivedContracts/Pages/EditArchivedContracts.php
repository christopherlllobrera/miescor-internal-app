<?php

namespace App\Filament\Resources\ArchivedContracts\Pages;

use App\Filament\Resources\ArchivedContracts\ArchivedContractsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditArchivedContracts extends EditRecord
{
    protected static string $resource = ArchivedContractsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
