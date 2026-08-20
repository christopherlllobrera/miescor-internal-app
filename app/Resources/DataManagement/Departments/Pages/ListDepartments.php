<?php

namespace App\Filament\Resources\DataManagement\Departments\Pages;

use App\Filament\Resources\DataManagement\Departments\DepartmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDepartments extends ListRecords
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Add Department'),
        ];
    }
}
