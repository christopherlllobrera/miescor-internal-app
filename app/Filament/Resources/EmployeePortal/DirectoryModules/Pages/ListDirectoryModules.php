<?php

namespace App\Filament\Resources\EmployeePortal\DirectoryModules\Pages;

use App\Filament\Resources\EmployeePortal\DirectoryModules\DirectoryModuleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDirectoryModules extends ListRecords
{
    protected static string $resource = DirectoryModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Directory Entry'),
        ];
    }
}
