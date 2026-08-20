<?php

namespace App\Filament\Resources\Fleet\IncidentManagement\Pages;

use App\Filament\Pages\Widgets\IncidentModuleOverview;
use App\Filament\Resources\Fleet\IncidentManagement\IncidentManagementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIncidentManagement extends ListRecords
{
    protected static string $resource = IncidentManagementResource::class;

    protected static ?string $title = 'Incidents';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            IncidentModuleOverview::class,
        ];
    }
}
