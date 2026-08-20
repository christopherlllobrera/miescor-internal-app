<?php

namespace App\Filament\Clusters\Toll\TollRoads\Pages;

use App\Filament\Clusters\Toll\TollRoads\TollRoadResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTollRoad extends EditRecord
{
    protected static string $resource = TollRoadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
