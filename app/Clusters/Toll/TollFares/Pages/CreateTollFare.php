<?php

namespace App\Filament\Clusters\Toll\TollFares\Pages;

use App\Filament\Clusters\Toll\TollFares\TollFareResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTollFare extends CreateRecord
{
    protected static string $resource = TollFareResource::class;
}
