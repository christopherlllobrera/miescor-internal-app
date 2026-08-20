<?php

namespace App\Filament\Clusters\Toll\TollPoints\Pages;

use App\Filament\Clusters\Toll\TollPoints\TollPointResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTollPoint extends CreateRecord
{
    protected static string $resource = TollPointResource::class;
}
