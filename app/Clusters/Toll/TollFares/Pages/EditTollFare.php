<?php

namespace App\Filament\Clusters\Toll\TollFares\Pages;

use App\Filament\Clusters\Toll\TollFares\TollFareResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTollFare extends EditRecord
{
    protected static string $resource = TollFareResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
