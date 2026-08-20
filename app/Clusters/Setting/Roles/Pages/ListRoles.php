<?php

namespace App\Filament\Clusters\Setting\Roles\Pages;

use App\Filament\Clusters\Setting\Roles\RolesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RolesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
