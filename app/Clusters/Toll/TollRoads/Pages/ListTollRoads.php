<?php

namespace App\Filament\Clusters\Toll\TollRoads\Pages;

use App\Filament\Clusters\Toll\TollRoads\TollRoadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTollRoads extends ListRecords
{
    protected static string $resource = TollRoadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
