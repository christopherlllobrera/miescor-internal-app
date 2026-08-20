<?php

namespace App\Filament\Resources\Fleet\TroubleCalls\Pages;

use App\Filament\Resources\Fleet\TroubleCalls\TroubleCallResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTroubleCall extends CreateRecord
{
    protected static string $resource = TroubleCallResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
