<?php

namespace App\Filament\Clusters\Toll\TollRoads\Pages;

use App\Filament\Clusters\Toll\TollRoads\TollRoadResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTollRoad extends CreateRecord
{
    protected static string $resource = TollRoadResource::class;
}
