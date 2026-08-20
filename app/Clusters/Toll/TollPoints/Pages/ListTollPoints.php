<?php

namespace App\Filament\Clusters\Toll\TollPoints\Pages;

use App\Filament\Clusters\Toll\TollPoints\TollPointResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTollPoints extends ListRecords
{
    protected static string $resource = TollPointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
