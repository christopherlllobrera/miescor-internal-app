<?php

namespace App\Filament\Resources\DataManagement\Employees\Pages;

use App\Filament\Resources\DataManagement\Employees\EmployeeResource;
use Carbon\Carbon;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

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
