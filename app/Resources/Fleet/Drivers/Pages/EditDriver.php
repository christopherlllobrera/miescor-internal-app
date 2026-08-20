<?php

namespace App\Filament\Resources\Fleet\Drivers\Pages;

use App\Filament\Resources\Fleet\Drivers\DriverResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDriver extends EditRecord
{
    protected static string $resource = DriverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
