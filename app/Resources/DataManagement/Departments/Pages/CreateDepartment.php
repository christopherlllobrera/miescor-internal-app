<?php

namespace App\Filament\Resources\DataManagement\Departments\Pages;

use App\Filament\Resources\DataManagement\Departments\DepartmentResource;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;

class CreateDepartment extends CreateRecord
{
    protected static string $resource = DepartmentResource::class;

    protected static ?string $title = 'Create Department';

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['CreatedBy'] = auth()->user()->id ?? null;
        $data['UpdatedBy'] = null;
        $data['DateCreated'] = Carbon::now();
        $data['DateUpdated'] = null;

        return $data;
    }
}
