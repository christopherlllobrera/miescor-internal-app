<?php

namespace App\Filament\Clusters\Setting\Roles\Pages;

use App\Filament\Clusters\Setting\Roles\RolesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRoles extends EditRecord
{
    protected static string $resource = RolesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
