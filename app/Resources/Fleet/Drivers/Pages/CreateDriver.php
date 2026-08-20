<?php

namespace App\Filament\Resources\Fleet\Drivers\Pages;

use App\Filament\Resources\Drivers\DriverResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDriver extends CreateRecord
{
    protected static string $resource = DriverResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
