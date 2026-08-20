<?php

namespace App\Filament\Resources\Fleet\IncidentManagement\Pages;

use App\Filament\Resources\Fleet\IncidentManagement\IncidentManagementResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditIncidentManagement extends EditRecord
{
    protected static string $resource = IncidentManagementResource::class;

    protected static ?string $title = 'Edit Incident';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
