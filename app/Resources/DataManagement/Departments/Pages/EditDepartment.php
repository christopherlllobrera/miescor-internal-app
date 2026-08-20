<?php

namespace App\Filament\Resources\DataManagement\Departments\Pages;

use App\Filament\Resources\DataManagement\Departments\DepartmentResource;
use Carbon\Carbon;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDepartment extends EditRecord
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['UpdatedBy'] = auth()->user()->id;
        $data['DateUpdated'] = Carbon::now();

        return $data;
    }
}
