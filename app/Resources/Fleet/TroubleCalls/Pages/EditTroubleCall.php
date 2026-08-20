<?php

namespace App\Filament\Resources\Fleet\TroubleCalls\Pages;

use App\Filament\Resources\Fleet\TroubleCalls\TroubleCallResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTroubleCall extends EditRecord
{
    protected static string $resource = TroubleCallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
