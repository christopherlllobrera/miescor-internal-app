<?php

namespace App\Filament\Resources\Fleet\Vehicles\Pages;

use App\Filament\Pages\Widgets\VehicleStatsOverview;
use App\Filament\Resources\Fleet\Vehicles\VehicleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVehicles extends ListRecords
{
    protected static string $resource = VehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            VehicleStatsOverview::class,

        ];
    }
}
