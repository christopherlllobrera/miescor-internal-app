<?php

namespace App\Filament\Clusters\Setting\Permissions\Pages;

use App\Filament\Clusters\Setting\Permissions\PermissionResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;
}
