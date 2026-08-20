<?php

namespace App\Filament\Resources\DataManagement\Employees\Pages;

use App\Filament\Resources\DataManagement\Employees\EmployeeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add Employee'),
        ];
    }
}
