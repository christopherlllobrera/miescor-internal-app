<?php

namespace App\Filament\Resources\EmployeePortal\DepartmentModules\Pages;

use App\Filament\Resources\EmployeePortal\DepartmentModules\DepartmentModuleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDepartmentModules extends ListRecords
{
    protected static string $resource = DepartmentModuleResource::class;

    protected static ?string $title = 'Department Pages';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add Department Page'),
        ];
    }
}
