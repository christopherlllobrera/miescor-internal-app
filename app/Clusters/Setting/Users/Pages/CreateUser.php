<?php

namespace App\Filament\Clusters\Setting\Users\Pages;

use App\Filament\Clusters\Setting\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
