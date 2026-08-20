<?php

namespace App\Filament\Clusters\Toll\TollPoints\Pages;

use App\Filament\Clusters\Toll\TollPoints\TollPointResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTollPoint extends EditRecord
{
    protected static string $resource = TollPointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
