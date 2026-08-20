<?php

namespace App\Filament\Resources\Fleet\IncidentManagement\Pages;

use App\Filament\Resources\Fleet\IncidentManagement\IncidentManagementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIncidentManagement extends CreateRecord
{
    protected static string $resource = IncidentManagementResource::class;

    protected static ?string $title = 'Create Incident';

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
