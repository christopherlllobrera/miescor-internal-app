<?php

namespace App\Filament\Resources\DataManagement\Employees\Pages;

use App\Filament\Resources\DataManagement\Employees\EmployeeResource;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['CreatedBy'] = auth()->user()->id ?? null;
        $data['UpdatedBy'] = null;
        $data['DateCreated'] = Carbon::now();
        $data['DateUpdated'] = null;

        return $data;
    }
}
