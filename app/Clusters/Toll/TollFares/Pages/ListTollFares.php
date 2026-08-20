<?php

namespace App\Filament\Clusters\Toll\TollFares\Pages;

use App\Filament\Clusters\Toll\TollFares\TollFareResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTollFares extends ListRecords
{
    protected static string $resource = TollFareResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
